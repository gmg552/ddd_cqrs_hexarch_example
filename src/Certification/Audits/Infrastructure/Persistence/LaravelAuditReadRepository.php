<?php

namespace Qalis\Certification\Audits\Infrastructure\Persistence;

use DateTime;
use Qalis\Certification\AuditReviewItemValues\Domain\AuditReviewItemValue;
use Qalis\Certification\AuditReviewItemValues\Domain\AuditReviewItemValuePass;
use Qalis\Certification\Audits\Application\Search\AuditResumeResponse;
use Qalis\Certification\Audits\Application\Search\AuditResumesResponse;
use Qalis\Certification\Audits\Application\Find\AuditResponse;
use Qalis\Certification\Audits\Domain\AuditReadRepository;
use Illuminate\Support\Facades\DB;
use Qalis\Certification\Audits\Domain\CheckTimestampOverlaps\CheckTimestampOverlapsRequest;
use Qalis\Certification\Audits\Domain\LoadAPIAuditDetail\GlobalgapAuditReportResponse;
use Qalis\Certification\Audits\Domain\SearchTimestampsForValidation\SearchTimestampsForValidationResponse;
use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Search\SchemeEntityFieldValueResponse;
use Qalis\Certification\Shared\Domain\AuditChecklists\AuditChecklistId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Operators\OperatorId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Audit;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\AuditChecklist;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\AuditChecklistScheme;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\AuditedItem;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Auditor;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\ChecklistAuditedItemTimestamp;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;
use stdClass;
use function Lambdish\Phunctional\map;
use Qalis\Shared\Domain\ValueObjects\FullNameValueObject;

class LaravelAuditReadRepository implements  AuditReadRepository {

    private int $userId;

    public function __construct(int $userId = null){
        $this->userId = $userId;
    }

    public function checkHasCertificateIssued(string $auditId): bool
    {
        return QueryBuilderUtils::notDeleted(DB::table('certificates')
            ->join('audits', 'audits.id', '=', 'certificates.audit_id'))
            ->whereRaw('hex(audits.uuid) = "'.$auditId.'"')
            ->exists();
    }

    public function isClosed(string $auditId): bool
    {

        $auditReviewItemValues = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('audit_review_item_values', 'audit_review_item_values.audit_id', '=', 'audits.id')
            ->join('audit_review_items', 'audit_review_item_values.audit_review_item_id', '=', 'audit_review_items.id'))
            ->whereRaw('hex(audits.uuid) = "'.$auditId.'" and (pass = "'.AuditReviewItemValuePass::NO.'" or pass is null)')
            ->selectRaw('audit_review_item_values.id, audit_review_item_values.pass, required')
            ->count();

        return $auditReviewItemValues === 0;

    }

    public function lastAuditEndDate(string $operatorId, string $schemeId): ?DateValueObject
    {
        $schemeId = Uuid2Id::resolve('schemes', $schemeId);
        $operatorId = Uuid2Id::resolve('operators', $operatorId);

        $auditDate = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('audited_schemes', 'audited_schemes.audit_id', '=', 'audits.id'))
            ->where('audits.operator_id', $operatorId)
            ->where('audited_schemes.scheme_id', $schemeId)
            ->selectRaw('max(audits.end_date) as auditDate')
            ->pluck('auditDate')
            ->first();

        return $auditDate ? new DateValueObject($auditDate) : null;
    }

    public function auditDateAfterSchemeProcedure(string $schemeProcedureId): ?DateValueObject
    {
        $schemeProcedure = QueryBuilderUtils::notDeleted(DB::table('scheme_orders'))
            ->whereRaw('hex(scheme_orders.uuid) = "'.$schemeProcedureId.'"')
            ->selectRaw('scheme_orders.date, scheme_orders.holder_id, scheme_orders.base_scheme_id')
            ->first();

        $auditDate = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('audited_schemes', 'audited_schemes.audit_id', '=', 'audits.id')
            ->join('ordered_schemes', 'ordered_schemes.id', '=', 'audited_schemes.ordered_scheme_id')
            ->join('scheme_orders', 'ordered_schemes.scheme_order_id', '=', 'scheme_orders.id'))
            ->where('audits.operator_id', $schemeProcedure->holder_id)
            ->where('scheme_orders.base_scheme_id', $schemeProcedure->base_scheme_id)
            ->where('audits.start_date', '>', $schemeProcedure->date)
            ->orderBy('audits.start_date')
            ->selectRaw('min(audits.start_date) as date')
            ->first();

        return ($auditDate && $auditDate->date) ? new DateValueObject($auditDate->date) : null;

    }

    public function auditDateBeforeSchemeProcedure(string $schemeProcedureId): ?DateValueObject
    {
        $schemeProcedure = QueryBuilderUtils::notDeleted(DB::table('scheme_orders'))
            ->whereRaw('hex(scheme_orders.uuid) = "'.$schemeProcedureId.'"')
            ->selectRaw('scheme_orders.date, scheme_orders.holder_id, scheme_orders.base_scheme_id')
            ->first();

        $auditDate = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('audited_schemes', 'audited_schemes.audit_id', '=', 'audits.id')
            ->join('ordered_schemes', 'ordered_schemes.id', '=', 'audited_schemes.ordered_scheme_id')
            ->join('scheme_orders', 'ordered_schemes.scheme_order_id', '=', 'scheme_orders.id'))
            ->where('audits.operator_id', $schemeProcedure->holder_id)
            ->where('scheme_orders.base_scheme_id', $schemeProcedure->base_scheme_id)
            ->where('audits.end_date', '<', $schemeProcedure->date)
            ->orderBy('audits.end_date', 'desc')
            ->selectRaw('max(audits.end_date) as date')
            ->first();

        return ($auditDate && $auditDate->date) ? new DateValueObject($auditDate->date) : null;

    }

    public function lastAuditEndDateBySchemeOrderBaseScheme(string $schemeOrderBaseSchemeId, string $operatorId): ?DateValueObject
    {
        $schemeOrderBaseSchemeId = Uuid2Id::resolve('schemes', $schemeOrderBaseSchemeId);
        $operatorId = Uuid2Id::resolve('operators', $operatorId);

        $auditDate = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('audited_schemes', 'audited_schemes.audit_id', '=', 'audits.id')
            ->join('ordered_schemes', 'ordered_schemes.id', '=', 'audited_schemes.ordered_scheme_id')
            ->join('scheme_orders', 'ordered_schemes.scheme_order_id', '=', 'scheme_orders.id'))
            ->where('holder_id', $operatorId)
            ->where('scheme_orders.base_scheme_id', $schemeOrderBaseSchemeId)
            ->selectRaw('max(audits.end_date) as auditDate')
            ->pluck('auditDate')
            ->first();

        return $auditDate ? new DateValueObject($auditDate) : null;

    }

    public function getNextAuditDateByIteration(string $iterationId): ?DateValueObject
    {
        $iteration = QueryBuilderUtils::notDeleted(DB::table('scheme_orders')
            ->join('ordered_schemes', 'ordered_schemes.scheme_order_id', '=', 'scheme_orders.id')
            ->join('iterations', 'iterations.ordered_scheme_id', '=', 'ordered_schemes.id'))
            ->whereRaw('hex(iterations.uuid) = "'.$iterationId.'"')
            ->selectRaw('scheme_orders.base_scheme_id, scheme_orders.holder_id, iterations.date')
            ->first();

        $date = QueryBuilderUtils::notDeleted(DB::table('audits'))
            ->where('audits.base_scheme_id', $iteration->base_scheme_id)
            ->where('audits.operator_id', $iteration->holder_id)
            ->whereDate('audits.start_date', '>', $iteration->date)
            ->pluck('audits.start_date')
            ->first();

        return $date ? new DateValueObject($date) : null;

    }

    public function getEarlierAuditDateByIteration(string $iterationId): ?DateValueObject
    {
        $iteration = QueryBuilderUtils::notDeleted(DB::table('scheme_orders')
            ->join('ordered_schemes', 'ordered_schemes.scheme_order_id', '=', 'scheme_orders.id')
            ->join('iterations', 'iterations.ordered_scheme_id', '=', 'ordered_schemes.id'))
            ->whereRaw('hex(iterations.uuid) = "'.$iterationId.'"')
            ->selectRaw('scheme_orders.base_scheme_id, scheme_orders.holder_id, iterations.date')
            ->first();

        $date = QueryBuilderUtils::notDeleted(DB::table('audits'))
            ->where('audits.base_scheme_id', $iteration->base_scheme_id)
            ->where('audits.operator_id', $iteration->holder_id)
            ->whereDate('audits.start_date', '<', $iteration->date)
            ->pluck('audits.start_date')
            ->first();

        return $date ? new DateValueObject($date) : null;
    }

    public function completeTimestampsForValidation(CheckTimestampOverlapsRequest $request): array
    {
        $response = [];
        $auditors = [];
        $auditChecklist = AuditChecklist::whereUuid($request->auditChecklistId())->first();

        foreach($request->toArray() as $item) {
            if (!isset($auditors[$item['auditor_id']])) $auditors[$item['auditor_id']] = Auditor::whereUuid($item['auditor_id'])->first();
            $this->addSearchTimestampsForValidationResponseToResponse($response,
                $item['id'],
                FullNameValueObject::toString($auditors[$item['auditor_id']]->subject->name, $auditors[$item['auditor_id']]->subject->surname1, $auditors[$item['auditor_id']]->subject->surname2),
                $auditors[$item['auditor_id']]->uuid,
                $item['date'],
                $item['start_time'],
                $item['end_time'],
                $auditChecklist->version->template->name,
                $item['type'],
                $item['type'] === CheckTimestampOverlapsRequest::AUDITED_ITEM_TYPE ? AuditedItem::whereUuid($item['auditedItemId'])->first()->code : null
            );
        }

        return $response;

    }

    public function searchTimestampsForValidationByAudit(string $auditId): array
    {
        $audit = Audit::whereUuid($auditId)->first();
        $response = [];

        foreach($audit->auditChecklists as $auditChecklist) {
            foreach ($auditChecklist->checklistAuditedItems as $checklistAuditedItem) {
                foreach ($checklistAuditedItem->checklistAuditedItemTimestamps as $timestamp) {
                    $this->addSearchTimestampsForValidationResponseToResponse($response,
                        $timestamp->uuid,
                        FullNameValueObject::toString($checklistAuditedItem->strawAuditor->subject->name, $checklistAuditedItem->strawAuditor->subject->surname1, $checklistAuditedItem->strawAuditor->subject->surname2),
                        $checklistAuditedItem->strawAuditor->uuid,
                        $timestamp->date,
                        $timestamp->start_time,
                        $timestamp->end_time,
                        $auditChecklist->version->template->name,
                        SearchTimestampsForValidationResponse::AUDITED_ITEM_TYPE,
                        $checklistAuditedItem->auditedItem ? $checklistAuditedItem->auditedItem->code : null
                    );
                }
            }
        }

        foreach($audit->auditChecklists as $auditChecklist) {
            if ($auditChecklist->strawAuditor) {
                foreach ($auditChecklist->auditChecklistSchemes as $auditChecklistScheme) {
                    if (($auditChecklistScheme->date) && ($auditChecklistScheme->start_time) && ($auditChecklistScheme->end_time)) {
                        $this->addSearchTimestampsForValidationResponseToResponse($response,
                            $auditChecklistScheme->uuid,
                            FullNameValueObject::toString($auditChecklist->strawAuditor->subject->name, $auditChecklist->strawAuditor->subject->surname1, $auditChecklist->strawAuditor->subject->surname2),
                            $auditChecklist->strawAuditor->uuid,
                            $auditChecklistScheme->date,
                            $auditChecklistScheme->start_time,
                            $auditChecklistScheme->end_time,
                            $auditChecklist->version->template->name,
                            SearchTimestampsForValidationResponse::CHECKLIST_TYPE
                        );
                    }
                }
            }
        }

        return $response;

    }

    private function addSearchTimestampsForValidationResponseToResponse(&$response, string $id, string $auditorFullName, string $auditorId, string $date, string $startTime, string $endTime, string $checklistTemplateName, string $type, ?string $auditedItemCode = null ) {
        array_push($response, new SearchTimestampsForValidationResponse(
            $id,
            $auditorFullName,
            $auditorId,
            new DateTime($date),
            $startTime,
            $endTime,
            $checklistTemplateName,
            $type,
            $auditedItemCode
        ));
    }

//    public function getQualityManagement(string $auditId): GlobalgapAuditReportResponse
//    {
//        $audit = Audit::whereUuid($auditId)->first();
//
//        $responseData = new GlobalgapAuditReportResponse();
//        $responseData->setName('Sistema');
//
//        $responseData->setAuditReportId('85f365d0-67b1-4a29-b925-442d9f6d3de2');
//        $responseData->setContactPerson(FullNameValueObject::toString($audit->operator->subject->name, $audit->operator->subject->surname1, $audit->operator->subject->surname2));
//        $responseData->setEmail($audit->operator->subject->email);
//        $responseData->setMainLocation('');
//        $responseData->setCompanyProfile('');
//        $responseData->setDateStart($audit->start_date);
//        $responseData->setDateEnd($audit->end_date);
//        $responseData->setNotes($audit->notes);
//        $responseData->setStatus(GlobalgapAuditReportResponse::IN_PROGRESS_STATUS);
//        $responseData->setRemoteAssessed(false);
//
//        //assessorteam
//        foreach($audit->auditChecklists as $auditChecklist) {
//            if ($auditChecklist->schemes()->where('code', 'GGIFAFVSIS')->exists()) {
//                if ($auditChecklist->strawAuditor) {
//                    $auditTeamMember = $auditChecklist->strawAuditor->auditTeamMembers()->where('audit_id', $audit->id)->first();
//                    $responseData->addAssessorTeam(
//                        FullNameValueObject::toString($auditChecklist->strawAuditor->subject->name, $auditChecklist->strawAuditor->subject->surname1, $auditChecklist->strawAuditor->subject->surname2),
//                        GlobalgapAuditReportResponse::IFA_ASSESSMENT,
//                        $this->getRoleFromAuditTeamMember($auditTeamMember),
//                        $auditChecklist->strawAuditor->code
//                    );
//                }
//            }
//        }
//
//        //KeyPersons
//        foreach($audit->operatorRepresentatives as $operatorRepresentative) {
//            $responseData->addKeyPerson(
//                FullNameValueObject::toString($operatorRepresentative->name, $operatorRepresentative->surname1, $operatorRepresentative->surname2),
//                FullNameValueObject::toString($audit->operator->subject->name, $audit->operator->subject->surname1, $audit->operator->subject->surname2),
//                $operatorRepresentative->position
//            );
//        }
//
//        //DatesandDuration
//        foreach($audit->auditChecklists as $auditChecklist) {
//            foreach($auditChecklist->auditChecklistSchemes as $auditChecklistScheme) {
//                if ($auditChecklistScheme->scheme->code === 'GGIFAFVSIS') {
//                    $responseData->addDateAndDuration(
//                        $auditChecklistScheme->date,
//                        $auditChecklist->strawAuditor ? $auditChecklist->strawAuditor->code : null,
//                        GlobalgapAuditReportResponse::IFAQMS_ASSESSMENT,
//                        $auditChecklistScheme->start_time,
//                        $auditChecklistScheme->end_time
//                    );
//                }
//            }
//        }
//
//        $assessmentItem = [];
//
//        $assessmentItem['Products_Scope'] = [];
//        foreach($audit->auditedSchemes as $auditedScheme) {
//            if ($auditedScheme->scheme->code == 'GGIFAFVCAM') {
//                foreach($auditedScheme->orderedScheme->products as $product) {
//                    array_push($assessmentItem['Products_Scope'], $product->code);
//                }
//            }
//        }
//        $assessmentItem['Products_Scope'] = array_unique($assessmentItem['Products_Scope']);
//
//        $assessmentItem['Products_Assessed'] = [];
//        $assessmentItem['Products_Harvest'] = [];
//        $assessmentItem['Products_PHU'] = [];
//        foreach($audit->products as $product) {
//            if ($product->pivot->crop_inspected) {
//                array_push($assessmentItem['Products_Assessed'], $product->code);
//            }
//            if ($product->pivot->harvest_inspected) {
//                array_push($assessmentItem['Products_Harvest'], $product->code);
//            }
//            if ($product->pivot->handling_inspected) {
//                array_push($assessmentItem['Products_PHU'], $product->code);
//            }
//        }
//        $assessmentItem['Products_Assessed'] = array_unique($assessmentItem['Products_Assessed']);
//        $assessmentItem['Products_Harvest'] = array_unique($assessmentItem['Products_Harvest']);
//        $assessmentItem['Products_PHU'] = array_unique($assessmentItem['Products_PHU']);
//
//        $assessmentItem['Products_New'] = [];
//
//        $assessmentItem['IsProducts_Stored'] = false;
//        $assessmentItem['Products_Stored'] = [];
//
//        $assessmentItem['IsProducts_ByOthers'] = false;
//        $assessmentItem['IsPPPO_PO'] = false;
//
//        $PPPO_PP_products = DB::table('cropped_areas')
//            ->join('iterated_cropped_areas', 'iterated_cropped_areas.cropped_area_id', '=', 'cropped_areas.id')
//            ->join('iterations', 'iterations.id', '=', 'iterated_cropped_areas.iteration_id')
//            ->join('audited_schemes', function ($join) use ($audit) {
//                $join->on('audited_schemes.cropped_area_iteration_id', '=', 'iterations.id')
//                    ->where('audited_schemes.audit_id', '=', $audit->id);
//            })
//            ->join('schemes', 'schemes.id', '=', 'audited_schemes.scheme_id')
//            ->join('products', 'products.id', '=', 'cropped_areas.product_id')
//            ->where('schemes.code', 'GGIFAFVCAM')
//            ->where('cropped_areas.parallel_production', true)
//            ->whereNull('cropped_areas.deleted_at')
//            ->whereNull('iterated_cropped_areas.deleted_at')
//            ->whereNull('iterations.deleted_at')
//            ->whereNull('audited_schemes.deleted_at')
//            ->whereNull('schemes.deleted_at')
//            ->distinct()
//            ->pluck('products.code')->toArray();
//
//        $assessmentItem['IsPPPO_PP'] = count($PPPO_PP_products) ? true : false;
//        $assessmentItem['PPPO_PP'] = $PPPO_PP_products;
//
//        $assessmentItem['CertificationCommittee'] = [];
//        $assessmentItem['CertificationCommittee']['CertCommitteeDecision'] = '';
//        $assessmentItem['CertificationCommittee']['CertCommitteeDate'] = null;
//        $assessmentItem['CertificationCommittee']['CertCommitteeDecisionBy'] = '';
//        $assessmentItem['CertificationCommittee']['CertCommitteeComments'] = '';
//        $assessmentItem['CertificationCommittee']['CertCycleStart'] = null;
//        $assessmentItem['CertificationCommittee']['CertCycleEnd'] = null;
//
//        $responseData->addIFAAssesment(
//            GlobalgapAuditReportResponse::SURVEILLANCE_ASSESSMENT_TYPE,
//            false,
//            $audit->schemeOrder->rewards_program,
//            $audit->schemeOrder->flexible_distribution,
//            false,
//            false,
//            '',
//            '',
//            false,
//            $assessmentItem['Products_Scope'],
//            $assessmentItem['Products_Assessed'],
//            $assessmentItem['Products_Harvest'],
//            $assessmentItem['Products_PHU'],
//            $assessmentItem['Products_New'],
//            $assessmentItem['IsProducts_Stored'],
//            $assessmentItem['Products_Stored'],
//            $assessmentItem['IsProducts_ByOthers'],
//            $assessmentItem['IsPPPO_PP'],
//            $assessmentItem['PPPO_PP'],
//            $assessmentItem['IsPPPO_PO'],
//            $assessmentItem['CertificationCommittee']
//        );
//
//
//        $isPPPOByOthers = []; //Consulta query para sacarlos
//        foreach($isPPPOByOthers as $isPPPOByOther) {
//            //$responseData->addAssessmentProductsByOthers('','','');
//        }
//
//        $PPPO_PO_products = DB::table('cropped_areas')
//            ->join('iterated_cropped_areas', 'iterated_cropped_areas.cropped_area_id', '=', 'cropped_areas.id')
//            ->join('iterations', 'iterations.id', '=', 'iterated_cropped_areas.iteration_id')
//            ->join('audited_schemes', function ($join) use ($audit) {
//                $join->on('audited_schemes.cropped_area_iteration_id', '=', 'iterations.id')
//                    ->where('audited_schemes.audit_id', '=', $audit->id);
//            })
//            ->join('schemes', 'schemes.id', '=', 'audited_schemes.scheme_id')
//            ->join('products', 'products.id', '=', 'cropped_areas.product_id')
//            ->where('schemes.code', 'GGIFAFVCAM')
//            ->where('cropped_areas.parallel_ownership', true)
//            ->whereNull('cropped_areas.deleted_at')
//            ->whereNull('iterated_cropped_areas.deleted_at')
//            ->whereNull('iterations.deleted_at')
//            ->whereNull('audited_schemes.deleted_at')
//            ->whereNull('schemes.deleted_at')
//            ->select('products.code')
//            ->distinct()
//            ->get();
//
//        foreach($PPPO_PO_products as $PPPO_PO_product) {
//            $responseData->addAssessmentPppoPo(
//                GlobalgapAuditReportResponse::IFA_ASSESSMENT,
//                $PPPO_PO_product->code,
//                '',
//                0,
//                GlobalgapAuditReportResponse::UNIT
//            );
//        }
//
//        $isPPPOTrades = []; //Consulta query para sacarlos
//        foreach($isPPPOTrades as $isPPPOTrade) {
//            //$responseData->addAssessmentPppoTrade('','','','','');
//        }
//
//        //Assessments.SitesAssessed
//        $sitesAssessed = DB::table('audited_items')
//            ->whereNull('deleted_at')
//            ->where('audit_id', $audit->id)
//            ->select('code', 'description')
//            ->get();
//
//        foreach($sitesAssessed as $siteAssessed) {
//            $responseData->addAssessmentSite(GlobalgapAuditReportResponse::IFA_ASSESSMENT, $siteAssessed->code, $siteAssessed->description);
//        }
//
//        return $responseData;
//
//    }
//
//    public function getAuditDetailsForGGApi(string $auditId): GlobalgapAuditReportResponse
//    {
//        $audit = Audit::whereUuid($auditId)->first();
//
//        $responseData = new GlobalgapAuditReportResponse();
//        $responseData->setName('Emplazamientos');
//
//        $responseData->setAuditReportId('85f365d0-67b1-4a29-b925-442d9f6d3de2');
//        $responseData->setContactPerson(FullNameValueObject::toString($audit->operator->subject->name, $audit->operator->subject->surname1, $audit->operator->subject->surname2));
//        $responseData->setEmail($audit->operator->subject->email);
//        $responseData->setMainLocation('');
//        $responseData->setCompanyProfile('');
//        $responseData->setDateStart($audit->start_date);
//        $responseData->setDateEnd($audit->end_date);
//        $responseData->setNotes($audit->notes);
//        $responseData->setStatus(GlobalgapAuditReportResponse::IN_PROGRESS_STATUS);
//        $responseData->setRemoteAssessed(false);
//
//        foreach($audit->auditTeamMembers as $auditTeamMember) {
//            $responseData->addAssessorTeam(
//                FullNameValueObject::toString($auditTeamMember->auditor->subject->name, $auditTeamMember->auditor->subject->surname1, $auditTeamMember->auditor->subject->surname2),
//                GlobalgapAuditReportResponse::IFA_ASSESSMENT,
//                $this->getRoleFromAuditTeamMember($auditTeamMember, 'Reviewer'),
//                $auditTeamMember->auditor->code
//            );
//        }
//
//        foreach($audit->operatorRepresentatives as $operatorRepresentative) {
//            $responseData->addKeyPerson(
//                FullNameValueObject::toString($operatorRepresentative->name, $operatorRepresentative->surname1, $operatorRepresentative->surname2),
//                FullNameValueObject::toString($audit->operator->subject->name, $audit->operator->subject->surname1, $audit->operator->subject->surname2),
//                $operatorRepresentative->position
//            );
//        }
//
//        //
//        foreach($audit->auditChecklists as $auditChecklist) {
//            if ($auditChecklist->schemes()->where('code', 'GGIFAFVCAM')->exists()) {
//                foreach($auditChecklist->checklistAuditedItems as $checklistAuditedItem) {
//                    foreach($checklistAuditedItem->checklistAuditedItemTimestamps as $timestamp) {
//                        $responseData->addDateAndDuration(
//                            $timestamp->date,
//                            $checklistAuditedItem->strawAuditor->code,
//                            $checklistAuditedItem->auditedItem->code,
//                            $timestamp->start_time,
//                            $timestamp->end_time
//                        );
//                    }
//                }
//            }
//        }
//
//        $assessmentItem = [];
//
//        $assessmentItem['Products_Scope'] = [];
//        foreach($audit->auditedSchemes as $auditedScheme) {
//            if (($auditedScheme->scheme->code == 'GGIFAFVCAM') || ($auditedScheme->scheme->code == 'GGIFAFVCEN')) {
//                foreach($auditedScheme->orderedScheme->products as $product) {
//                    array_push($assessmentItem['Products_Scope'], $product->code);
//                }
//            }
//        }
//        $assessmentItem['Products_Scope'] = array_unique($assessmentItem['Products_Scope']);
//
//        $assessmentItem['Products_Assessed'] = [];
//        $assessmentItem['Products_Harvest'] = [];
//        $assessmentItem['Products_PHU'] = [];
//        foreach($audit->products as $product) {
//            if ($product->pivot->crop_inspected) {
//                array_push($assessmentItem['Products_Assessed'], $product->code);
//            }
//            if ($product->pivot->harvest_inspected) {
//                array_push($assessmentItem['Products_Harvest'], $product->code);
//            }
//            if ($product->pivot->handling_inspected) {
//                array_push($assessmentItem['Products_PHU'], $product->code);
//            }
//        }
//        $assessmentItem['Products_Assessed'] = array_unique($assessmentItem['Products_Assessed']);
//        $assessmentItem['Products_Harvest'] = array_unique($assessmentItem['Products_Harvest']);
//        $assessmentItem['Products_PHU'] = array_unique($assessmentItem['Products_PHU']);
//
//        $assessmentItem['Products_New'] = [];
//
//        $assessmentItem['IsProducts_Stored'] = false;
//        $assessmentItem['Products_Stored'] = [];
//
//        $assessmentItem['IsProducts_ByOthers'] = false;
//        $assessmentItem['IsPPPO_PO'] = false;
//
//        $PPPO_PP_products = DB::table('cropped_areas')
//            ->join('iterated_cropped_areas', 'iterated_cropped_areas.cropped_area_id', '=', 'cropped_areas.id')
//            ->join('iterations', 'iterations.id', '=', 'iterated_cropped_areas.iteration_id')
//            ->join('audited_schemes', function ($join) use ($audit) {
//                $join->on('audited_schemes.cropped_area_iteration_id', '=', 'iterations.id')
//                    ->where('audited_schemes.audit_id', '=', $audit->id);
//            })
//            ->join('schemes', 'schemes.id', '=', 'audited_schemes.scheme_id')
//            ->join('products', 'products.id', '=', 'cropped_areas.product_id')
//            ->where('schemes.code', 'GGIFAFVCAM')
//            ->where('cropped_areas.parallel_production', true)
//            ->whereNull('cropped_areas.deleted_at')
//            ->whereNull('iterated_cropped_areas.deleted_at')
//            ->whereNull('iterations.deleted_at')
//            ->whereNull('audited_schemes.deleted_at')
//            ->whereNull('schemes.deleted_at')
//            ->distinct()
//            ->pluck('products.code')->toArray();
//
//        $assessmentItem['IsPPPO_PP'] = count($PPPO_PP_products) ? true : false;
//        $assessmentItem['PPPO_PP'] = $PPPO_PP_products;
//
//        $assessmentItem['CertificationCommittee'] = [];
//        $assessmentItem['CertificationCommittee']['CertCommitteeDecision'] = '';
//        $assessmentItem['CertificationCommittee']['CertCommitteeDate'] = null;
//        $assessmentItem['CertificationCommittee']['CertCommitteeDecisionBy'] = '';
//        $assessmentItem['CertificationCommittee']['CertCommitteeComments'] = '';
//        $assessmentItem['CertificationCommittee']['CertCycleStart'] = null;
//        $assessmentItem['CertificationCommittee']['CertCycleEnd'] = null;
//
//        $responseData->addIFAAssesment(
//            GlobalgapAuditReportResponse::SURVEILLANCE_ASSESSMENT_TYPE,
//            false,
//            $audit->schemeOrder->rewards_program,
//            $audit->schemeOrder->flexible_distribution,
//            false,
//            false,
//            '',
//            '',
//            false,
//            $assessmentItem['Products_Scope'],
//            $assessmentItem['Products_Assessed'],
//            $assessmentItem['Products_Harvest'],
//            $assessmentItem['Products_PHU'],
//            $assessmentItem['Products_New'],
//            $assessmentItem['IsProducts_Stored'],
//            $assessmentItem['Products_Stored'],
//            $assessmentItem['IsProducts_ByOthers'],
//            $assessmentItem['IsPPPO_PP'],
//            $assessmentItem['PPPO_PP'],
//            $assessmentItem['IsPPPO_PO'],
//            $assessmentItem['CertificationCommittee']
//        );
//
//
//        $isPPPOByOthers = []; //Consulta query para sacarlos
//        foreach($isPPPOByOthers as $isPPPOByOther) {
//            //$responseData->addAssessmentProductsByOthers('','','');
//        }
//
//        $PPPO_PO_products = DB::table('cropped_areas')
//            ->join('iterated_cropped_areas', 'iterated_cropped_areas.cropped_area_id', '=', 'cropped_areas.id')
//            ->join('iterations', 'iterations.id', '=', 'iterated_cropped_areas.iteration_id')
//            ->join('audited_schemes', function ($join) use ($audit) {
//                $join->on('audited_schemes.cropped_area_iteration_id', '=', 'iterations.id')
//                    ->where('audited_schemes.audit_id', '=', $audit->id);
//            })
//            ->join('schemes', 'schemes.id', '=', 'audited_schemes.scheme_id')
//            ->join('products', 'products.id', '=', 'cropped_areas.product_id')
//            ->where('schemes.code', 'GGIFAFVCAM')
//            ->where('cropped_areas.parallel_ownership', true)
//            ->whereNull('cropped_areas.deleted_at')
//            ->whereNull('iterated_cropped_areas.deleted_at')
//            ->whereNull('iterations.deleted_at')
//            ->whereNull('audited_schemes.deleted_at')
//            ->whereNull('schemes.deleted_at')
//            ->select('products.code')
//            ->distinct()
//            ->get();
//
//        foreach($PPPO_PO_products as $PPPO_PO_product) {
//            $responseData->addAssessmentPppoPo(
//                GlobalgapAuditReportResponse::IFA_ASSESSMENT,
//                $PPPO_PO_product->code,
//                '',
//                0,
//                GlobalgapAuditReportResponse::UNIT
//            );
//        }
//
//        $isPPPOTrades = []; //Consulta query para sacarlos
//        foreach($isPPPOTrades as $isPPPOTrade) {
//            //$responseData->addAssessmentPppoTrade('','','','','');
//        }
//
//        //Assessments.SitesAssessed
//        $sitesAssessed = DB::table('audited_items')
//            ->whereNull('deleted_at')
//            ->where('audit_id', $audit->id)
//            ->select('code', 'description')
//            ->get();
//
//        foreach($sitesAssessed as $siteAssessed) {
//            $responseData->addAssessmentSite(GlobalgapAuditReportResponse::IFA_ASSESSMENT, $siteAssessed->code, $siteAssessed->description);
//        }
//
//        return $responseData;
//
//    }

//    private function getRoleFromAuditTeamMember($teamMember, string $defaultValue = null) {
//        $role = $defaultValue;
//        if ($teamMember->role) {
//            if (strpos("Auditor", $teamMember->role->name) !== false) $role = GlobalgapAuditReportResponse::AUDITOR_ROLE;
//            if (strpos("Inspector", $teamMember->role->name) !== false) $role = GlobalgapAuditReportResponse::INSPECTOR_ROLE;
//        }
//        return $role;
//    }

    public function getRealAuditorIdByChecklist(AuditChecklistId $auditChecklistId): ?string
    {
        $auditChecklist = AuditChecklist::whereUuid($auditChecklistId->value())->first();
        return $auditChecklist->audit->realAuditor ? $auditChecklist->audit->realAuditor->uuid : null;
    }

    public function checkIfHasAChecklistWithThisVersion(string $auditId, string $versionId): bool
    {
        return DB::table('audit_checklists')
            ->join('audits', 'audits.id', '=', 'audit_checklists.audit_id')
            ->join('checklist_versions', 'checklist_versions.id', '=', 'audit_checklists.checklist_version_id')
            ->whereRaw('hex(audits.uuid) = "'.$auditId.'"')
            ->whereRaw('hex(checklist_versions.uuid) = "'.$versionId.'"')
            ->exists();
    }

    public function find(AuditId $auditId, SchemeEntityFieldsResponse $fields): AuditResponse
    {
        $audit = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->leftJoin('auditors as real_auditors', 'audits.real_chief_auditor_id', '=', 'real_auditors.id')
            ->leftJoin('auditors as straw_auditors', 'audits.straw_chief_auditor_id', '=', 'straw_auditors.id')
            ->leftJoin('audit_types', 'audits.audit_type_id', '=', 'audit_types.id')
            ->leftJoin('audit_control_level_types', 'audits.control_level_id', '=', 'audit_control_level_types.id')
            ->leftJoin('scheme_orders', 'scheme_orders.id', '=', 'audits.scheme_order_id')
            ->leftJoin('audit_reviewers', 'audit_reviewers.id', '=', 'audits.audit_reviewer_id')
            ->leftJoin('audit_decision_makers', 'audit_decision_makers.id', '=', 'audits.audit_decision_maker_id')
            ->join('schemes', 'audits.base_scheme_id', '=', 'schemes.id')
            ->join('services', 'services.id', '=', 'schemes.id')
            ->join('operators', 'audits.operator_id', '=', 'operators.id')
            ->join('subjects', 'subjects.id', '=', 'operators.id'))
            ->whereRaw('hex(audits.uuid) = "'.$auditId->value().'"')
            ->selectRaw(
                '
                audits.*,
                (select operator_cb_numbers.number
                from schemes left join operator_cb_numeration_ranges on operator_cb_numeration_ranges.id = schemes.operator_cb_numeration_range_id
                left join operator_cb_numbers on operator_cb_numbers.operator_cb_numeration_range_id = operator_cb_numeration_ranges.id
                where schemes.id = audits.base_scheme_id and operator_cb_numbers.operator_id = audits.operator_id limit 1) as operatorNumber,
                (select operator_codes.code
                from operator_codes left join operator_code_ranges on operator_codes.operator_code_range_id = operator_code_ranges.id
                left join scheme_operator_code_ranges on scheme_operator_code_ranges.range_id = operator_code_ranges.id
                where operator_codes.operator_id = audits.operator_id and scheme_operator_code_ranges.scheme_id = audits.base_scheme_id
                order by scheme_operator_code_ranges.order asc limit 1) as operatorSchemeCode,
                lower(hex(audits.uuid)) as id,
                lower(hex(operators.uuid)) as operatorId,
                subjects.name as operatorName,
                subjects.surname1 as operatorSurname1,
                subjects.surname2 as operatorSurname2,
                lower(hex(schemes.uuid)) as baseSchemeId,
                services.name as baseSchemeName,
                lower(hex(real_auditors.uuid)) as realChiefAuditorId,
                lower(hex(straw_auditors.uuid)) as strawChiefAuditorId,
                lower(hex(audit_types.uuid)) as auditTypeId,
                lower(hex(audit_control_level_types.uuid)) as controlLevelId,
                lower(hex(audit_reviewers.uuid)) as auditReviewerId,
                lower(hex(audit_decision_makers.uuid)) as auditDecisionMakerId,
                drive_folder_id as driveFolderId,
                start_date as startDate,
                end_date as endDate,
                audits.review_notes,
                signature_date as signatureDate,
                signature_location as signatureLocation,
                scheme_orders.date as schemeOrderDate')
            ->first();

        $entityFieldsValueResponse = [];
        foreach($fields as $field) {
            array_push($entityFieldsValueResponse, new SchemeEntityFieldValueResponse(
                $field->id(),
                $field->label(),
                $field->type(),
                $field->name(),
                $field->stringValues(),
                $audit->{$field->name()}
            ));
        }

        $result = new AuditResponse(
            $audit->id,
            $audit->operatorId,
            FullNameValueObject::toString($audit->operatorName, $audit->operatorSurname1, $audit->operatorSurname2),
            $audit->baseSchemeId,
            $audit->baseSchemeName,
            $audit->operatorSchemeCode,
            $audit->operatorNumber,
            $audit->realChiefAuditorId,
            $audit->auditTypeId,
            $audit->strawChiefAuditorId,
            $audit->controlLevelId,
            $audit->startDate? new DateValueObject($audit->startDate): null,
            $audit->endDate? new DateValueObject($audit->endDate): null,
            $audit->workdays,
            $audit->number,
            $audit->notes,
            $audit->signatureDate? new DateValueObject($audit->signatureDate): null,
            $audit->signatureLocation,
            $audit->schemeOrderDate,
            $entityFieldsValueResponse,
            $audit->driveFolderId,
            $audit->review_notes,
            $audit->auditReviewerId,
            $audit->auditDecisionMakerId
        );

        return $result;
    }

    public function searchByOperatorIdAndSchemeId(OperatorId $operatorId, SchemeId $baseSchemeId): AuditResumesResponse
    {
        $audits = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('operators', 'audits.operator_id', '=', 'operators.id')
            ->join('subjects', 'operators.id', '=', 'subjects.id')
            ->join('schemes', 'schemes.id', '=', 'audits.base_scheme_id')
            ->join('services', 'services.id', '=', 'schemes.id')
            ->leftJoin('audit_types', 'audit_types.id', '=', 'audits.audit_type_id'))
            ->whereRaw('hex(operators.uuid) = "'.$operatorId->value().'"')
            ->whereRaw('hex(schemes.uuid) = "'.$baseSchemeId->value().'"')
            ->orderByRaw('audits.start_date desc, audits.number asc')
            ->select(DB::raw("subjects.name as operator_name, subjects.surname1 as operator_surname1, subjects.surname2 as operator_surname2, hex(audits.uuid) as uuid, audits.start_date, audits.end_date, services.name as service_name, audit_types.name as audit_type_name, audits.number"))
            ->get();

        //Conversión de datos a clase
        $result = new AuditResumesResponse(...map($this->toResponse(), $audits));
        return $result;

    }

    private function toResponse(): callable
    {
        return static fn(stdClass $audit) => new AuditResumeResponse(
            $audit->uuid,
            FullNameValueObject::toString($audit->operator_name, $audit->operator_surname1, $audit->operator_surname2),
            $audit->service_name,
            $audit->start_date,
            $audit->end_date,
            $audit->audit_type_name,
            $audit->number
        );
    }

    public function numberDuplicatedForThisOperatorAndYear(int $number, string $operatorId, string $year, string $baseSchemeId, string $auditId) : bool {

        return Audit::join('operators', 'operators.id', 'audits.operator_id')
        ->join('schemes', 'schemes.id', 'audits.base_scheme_id')
        ->where('number', $number)
        ->whereRaw('hex(operators.uuid) = "'.$operatorId.'"')
        ->whereRaw('year(start_date) = "'.$year.'"')
        ->whereRaw('hex(schemes.uuid) = "'.$baseSchemeId.'"')
        ->whereRaw('hex(audits.uuid) != "'.$auditId.'"')
        ->exists();

    }

}
