<?php

namespace Qalis\Certification\Audits\Infrastructure\Persistence;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;
use Qalis\Certification\Auditors\Infrastructure\Persistence\LaravelAuditorReadRepository;
use Qalis\Certification\Audits\Domain\AuditCode;
use Qalis\Certification\Audits\Domain\AuditDriveFolderId;
use Qalis\Certification\Audits\Domain\AuditNotes;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Illuminate\Support\Facades\DB;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditDatePeriod;
use Qalis\Certification\Audits\Domain\AuditNumber;
use Qalis\Certification\Audits\Domain\AuditReviewNotes;
use Qalis\Certification\Audits\Domain\AuditSignatureLocation;
use Qalis\Certification\Shared\Domain\AuditDecisionMakers\AuditDecisionMakerId;
use Qalis\Certification\Shared\Domain\AuditedSchemes\AuditedSchemeId;
use Qalis\Certification\Shared\Domain\Auditors\AuditorId;
use Qalis\Certification\Shared\Domain\AuditReviewers\AuditReviewerId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\AuditTypes\AuditTypeId;
use Qalis\Certification\Shared\Domain\ControlLevels\ControlLevelId;
use Qalis\Certification\Shared\Domain\Operators\OperatorId;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\SchemeEntityFieldValues;
use Qalis\Certification\Shared\Domain\SchemeOrders\SchemeOrderId;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Shared\Domain\Criteria\Criteria;
use Qalis\Shared\Domain\Criteria\Filter;
use Qalis\Shared\Domain\Criteria\FilterExpression;
use Qalis\Shared\Domain\Criteria\FilterField;
use Qalis\Shared\Domain\Criteria\FilterOperator;
use Qalis\Shared\Domain\Criteria\FilterValue;
use Qalis\Shared\Domain\UnitOfWork;
use Qalis\Shared\Domain\ValueObjects\BoolValueObject;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Audit as AuditEloquent;

use Qalis\Shared\Infrastructure\Persistence\MySql\TableMaps\AuditsDBTable;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\CriteriaToQueryBuilderMapper;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\DBTableToQueryBuilder;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

use function Lambdish\Phunctional\map;
use stdClass;

class LaravelAuditRepository implements AuditRepository {

    private int $userId;

    public function __construct(int $userId = null){
        $this->userId = $userId;
    }

    public function delete(AuditId $id): void
    {

        $audit = AuditEloquent::whereUuid($id->value())->first();

        $audit->auditTeamMembers()->forceDelete();
        $audit->operatorRepresentatives()->forceDelete();
        $audit->products()->detach();

        foreach($audit->auditedItems as $auditedItem) {
            if ($auditedItem->auditedOperator) {
                $auditedItem->auditedOperator->productionUnits()->detach();
                $auditedItem->auditedOperator->forceDelete();
            }
            if ($auditedItem->auditedHandlingUnit) $auditedItem->auditedHandlingUnit->forceDelete();
            if ($auditedItem->auditedProductionUnit) $auditedItem->auditedProductionUnit->forceDelete();
            $auditedItem->auditedSchemes()->detach();
            $auditedItem->nonConformities()->detach();
        }

        foreach($audit->caps as $cap) {
            $cap->correctiveActions()->forceDelete();
        }

        $audit->caps()->forceDelete();

        $audit->nonConformities()->forceDelete();
        $audit->auditedSchemes()->forceDelete();

        $audit->massBalanceRecords()->forceDelete();
        $audit->traceabilityRecords()->forceDelete();
        $audit->auditedItems()->forceDelete();


        $audit->auditReviewItemValues()->forceDelete();
        $audit->auditDecisions()->forceDelete();

        $audit->forceDelete();

    }

    public function findByAuditedScheme(AuditedSchemeId $auditedSchemeId): Audit
    {
        $audit = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->leftJoin('auditors as real_auditors', 'audits.real_chief_auditor_id', '=', 'real_auditors.id')
            ->leftJoin('auditors as straw_auditors', 'audits.straw_chief_auditor_id', '=', 'straw_auditors.id')
            ->leftJoin('audit_types', 'audits.audit_type_id', '=', 'audit_types.id')
            ->leftJoin('audit_control_level_types', 'audits.control_level_id', '=', 'audit_control_level_types.id')
            ->leftJoin('scheme_orders', 'scheme_orders.id', '=', 'audits.scheme_order_id')
            ->join('audited_schemes', 'audits.id', '=', 'audited_schemes.audit_id')
            ->join('schemes', 'audits.base_scheme_id', '=', 'schemes.id')
            ->join('operators', 'audits.operator_id', '=', 'operators.id')
            ->leftJoin('audit_reviewers', 'audit_reviewers.id', '=', 'audits.audit_reviewer_id')
            ->leftJoin('audit_decision_makers', 'audit_decision_makers.id', '=', 'audits.audit_decision_maker_id'))
            ->whereRaw('hex(audited_schemes.uuid) = "'.$auditedSchemeId->value().'"')
            ->selectRaw(
                'lower(hex(audits.uuid)) as id,
                lower(hex(audit_types.uuid)) as auditTypeId,
                lower(hex(operators.uuid)) as operatorId,
                lower(hex(real_auditors.uuid)) as realChiefAuditorId,
                lower(hex(straw_auditors.uuid)) as strawChiefAuditorId,
                lower(hex(audit_control_level_types.uuid)) as controlLevelId,
                lower(hex(schemes.uuid)) as baseSchemeId,
                lower(hex(scheme_orders.uuid)) as schemeOrderId,
                lower(hex(audit_reviewers.uuid)) as auditReviewerId,
                lower(hex(audit_decision_makers.uuid)) as auditDecisionMakerId,
                audits.review_notes,
                start_date as startDate,
                end_date as endDate,
                audits.workdays,
                audits.code,
                audits.is_closed,
                audits.drive_folder_id,
                audits.signature_location as signatureLocation,
                number,
                audits.notes,
                signature_date as signatureDate')
            ->first();

        return new Audit(
            new AuditId($audit->id),
            new OperatorId($audit->operatorId),
            new SchemeId($audit->baseSchemeId),
            new AuditDatePeriod($audit->startDate ? new DateValueObject($audit->startDate) : null, $audit->endDate ? new DateValueObject($audit->endDate) : null),
            new BoolValueObject((bool)$audit->is_closed),
            $audit->realChiefAuditorId ? new AuditorId($audit->realChiefAuditorId) : null,
            $audit->auditTypeId ? new AuditTypeId($audit->auditTypeId) : null,
            $audit->strawChiefAuditorId ? new AuditorId($audit->strawChiefAuditorId) : null,
            $audit->controlLevelId ? new ControlLevelId($audit->controlLevelId) : null,
            $audit->schemeOrderId ? new SchemeOrderId($audit->schemeOrderId) : null,
            $audit->workdays,
            $audit->number ? new AuditNumber($audit->number) : null,
            $audit->code ? new AuditCode($audit->code) : null,
            $audit->notes ? new AuditNotes($audit->notes) : null,
            $audit->signatureDate ? new DateTimeValueObject($audit->signatureDate) : null,
            $audit->signatureLocation ? new AuditSignatureLocation($audit->signatureLocation) : null,
            $audit->drive_folder_id ? new AuditDriveFolderId($audit->drive_folder_id) : null,
            $audit->review_notes ? new AuditReviewNotes($audit->review_notes) : null,
            $audit->auditDecisionMakerId ? new AuditDecisionMakerId($audit->auditDecisionMakerId) : null,
            $audit->auditReviewerId ? new AuditReviewerId($audit->auditReviewerId) : null
        );

    }


    public function searchByCriteria(Criteria $criteria) : array {
        $queryBuilderWithCriteria = $this->searchQueryBuilder($criteria);
        return map($this->queryBuilderRecordToAudit(), $queryBuilderWithCriteria->get());
    }

    public function find2(AuditId $auditId) : Audit {

        $criteria = new Criteria(
            new FilterExpression(
                new Filter(
                    new FilterField('Audit','id'),
                    new FilterOperator(FilterOperator::EQUAL),
                    new FilterValue($auditId->value())
                )
            )
        );
        $result = $this->searchByCriteria($criteria);
        $this->ensureExist($result, $auditId);
        return $result[0];
    }

    private function ensureExist(array $queryResult, AuditId $auditId){
        if (count($queryResult) == 0) {
            throw new InvalidArgumentException("No se encuentra la auditoría con id <".$auditId->value().">");
        }
    }

    private function queryBuilderRecordToAudit() : callable {
        return static fn(stdClass $audit) => new Audit(
            new AuditId($audit->id),
            new OperatorId($audit->operatorId),
            new SchemeId($audit->baseSchemeId),
            new AuditDatePeriod($audit->startDate ? new DateValueObject($audit->startDate) : null, $audit->endDate ? new DateValueObject($audit->endDate) : null),
            new BoolValueObject((bool)$audit->is_closed),
            $audit->realChiefAuditorId ? new AuditorId($audit->realChiefAuditorId) : null,
            $audit->auditTypeId ? new AuditTypeId($audit->auditTypeId) : null,
            $audit->strawChiefAuditorId ? new AuditorId($audit->strawChiefAuditorId) : null,
            $audit->controlLevelId ? new ControlLevelId($audit->controlLevelId) : null,
            $audit->schemeOrderId ? new SchemeOrderId($audit->schemeOrderId) : null,
            $audit->workdays,
            $audit->number ? new AuditNumber($audit->number) : null,
            $audit->code ? new AuditCode($audit->code) : null,
            $audit->notes ? new AuditNotes($audit->notes) : null,
            $audit->signatureDate ? new DateTimeValueObject($audit->signatureDate) : null ,
            $audit->signatureLocation ? new AuditSignatureLocation($audit->signatureLocation) : null,
            $audit->drive_folder_id ? new AuditDriveFolderId($audit->drive_folder_id) : null,
            $audit->review_notes ? new AuditReviewNotes($audit->review_notes) : null,
            $audit->auditDecisionMakerId ? new AuditDecisionMakerId($audit->auditDecisionMakerId) : null,
            $audit->auditReviewerId ? new AuditReviewerId($audit->auditReviewerId) : null
        );
    }

    public function create(Audit $audit): void {
        //obtener el id del operator a partir de su uuid. Usar Uuid2Id
        //obtener el id del esquema base a partir de su uuid. Usar Uuid2Id
        //obtener el id del auditor jefe real a partir de $this->userId. Usar LaravelAuditorReadRepository->getAuditorIdByUserId

        $real_chief_auditor_id = LaravelAuditorReadRepository::getAuditorIdByUserId($this->userId);

        $operator_id = Uuid2Id::resolve('operators', $audit->operatorId());
        $scheme_id = Uuid2Id::resolve('schemes', $audit->baseSchemeId());

        DB::table('audits')->insert(
            [
                'uuid' => $audit->id()->binValue(),
                'operator_id' => $operator_id,
                'base_scheme_id' => $scheme_id,
                'real_chief_auditor_id' => $real_chief_auditor_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }

    private function searchQueryBuilder(Criteria $criteria) : Builder {

        $query = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->leftJoin('auditors as real_chief_auditors', 'audits.real_chief_auditor_id', '=', 'real_chief_auditors.id')
            ->leftJoin('auditors as straw_chief_auditors', 'audits.straw_chief_auditor_id', '=', 'straw_chief_auditors.id')
            ->leftJoin('audit_types', 'audits.audit_type_id', '=', 'audit_types.id')
            ->leftJoin('audit_control_level_types', 'audits.control_level_id', '=', 'audit_control_level_types.id')
            ->leftJoin('scheme_orders', 'scheme_orders.id', '=', 'audits.scheme_order_id')
            ->join('schemes', 'audits.base_scheme_id', '=', 'schemes.id')
            ->join('operators', 'audits.operator_id', '=', 'operators.id')
            ->leftJoin('audit_reviewers', 'audit_reviewers.id', '=', 'audits.audit_reviewer_id')
            ->leftJoin('audit_decision_makers', 'audit_decision_makers.id', '=', 'audits.audit_decision_maker_id'))
            ->selectRaw(
                'lower(hex(audits.uuid)) as id,
                lower(hex(audit_types.uuid)) as auditTypeId,
                lower(hex(operators.uuid)) as operatorId,
                lower(hex(real_chief_auditors.uuid)) as realChiefAuditorId,
                lower(hex(straw_chief_auditors.uuid)) as strawChiefAuditorId,
                lower(hex(audit_control_level_types.uuid)) as controlLevelId,
                lower(hex(schemes.uuid)) as baseSchemeId,
                lower(hex(scheme_orders.uuid)) as schemeOrderId,
                start_date as startDate,
                end_date as endDate,
                workdays,
                number,
                drive_folder_id,
                audits.is_closed,
                lower(hex(audit_reviewers.uuid)) as auditReviewerId,
                lower(hex(audit_decision_makers.uuid)) as auditDecisionMakerId,
                audits.review_notes,
                audits.code,
                audits.notes,
                signature_date as signatureDate,
                signature_location as signatureLocation');


        (new CriteriaToQueryBuilderMapper($criteria, $query))->apply();

        return $query;
    }

    public function find(AuditId $auditId): Audit
    {

        $audit = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->leftJoin('auditors as real_auditors', 'audits.real_chief_auditor_id', '=', 'real_auditors.id')
            ->leftJoin('auditors as straw_auditors', 'audits.straw_chief_auditor_id', '=', 'straw_auditors.id')
            ->leftJoin('audit_types', 'audits.audit_type_id', '=', 'audit_types.id')
            ->leftJoin('audit_control_level_types', 'audits.control_level_id', '=', 'audit_control_level_types.id')
            ->leftJoin('scheme_orders', 'scheme_orders.id', '=', 'audits.scheme_order_id')
            ->join('schemes', 'audits.base_scheme_id', '=', 'schemes.id')
            ->join('operators', 'audits.operator_id', '=', 'operators.id')
            ->leftJoin('audit_reviewers', 'audit_reviewers.id', '=', 'audits.audit_reviewer_id')
            ->leftJoin('audit_decision_makers', 'audit_decision_makers.id', '=', 'audits.audit_decision_maker_id'))
            ->whereRaw('hex(audits.uuid) = "'.$auditId->value().'"')
            ->selectRaw(
                'lower(hex(audits.uuid)) as id,
                lower(hex(audit_types.uuid)) as auditTypeId,
                lower(hex(operators.uuid)) as operatorId,
                lower(hex(real_auditors.uuid)) as realChiefAuditorId,
                lower(hex(straw_auditors.uuid)) as strawChiefAuditorId,
                lower(hex(audit_control_level_types.uuid)) as controlLevelId,
                lower(hex(schemes.uuid)) as baseSchemeId,
                lower(hex(scheme_orders.uuid)) as schemeOrderId,
                lower(hex(audit_reviewers.uuid)) as auditReviewerId,
                lower(hex(audit_decision_makers.uuid)) as auditDecisionMakerId,
                audits.review_notes,
                start_date as startDate,
                end_date as endDate,
                workdays,
                number,
                audits.is_closed,
                audits.code,
                audits.drive_folder_id,
                audits.notes,
                signature_date as signatureDate,
                signature_location as signatureLocation')
            ->first();

        if (!$audit) {
            throw new InvalidArgumentException("No se encuentra la auditoría con id <".$auditId->value().">");
        }

        return new Audit(
            new AuditId($audit->id),
            new OperatorId($audit->operatorId),
            new SchemeId($audit->baseSchemeId),
            new AuditDatePeriod($audit->startDate ? new DateValueObject($audit->startDate) : null, $audit->endDate ? new DateValueObject($audit->endDate) : null),
            new BoolValueObject((bool)$audit->is_closed),
            $audit->realChiefAuditorId ? new AuditorId($audit->realChiefAuditorId) : null,
            $audit->auditTypeId ? new AuditTypeId($audit->auditTypeId) : null,
            $audit->strawChiefAuditorId ? new AuditorId($audit->strawChiefAuditorId) : null,
            $audit->controlLevelId ? new ControlLevelId($audit->controlLevelId) : null,
            $audit->schemeOrderId ? new SchemeOrderId($audit->schemeOrderId) : null,
            $audit->workdays,
            $audit->number ? new AuditNumber($audit->number) : null,
            $audit->code ? new AuditCode($audit->code) : null,
            $audit->notes ? new AuditNotes($audit->notes) : null,
            $audit->signatureDate ? new DateTimeValueObject($audit->signatureDate) : null ,
            $audit->signatureLocation ? new AuditSignatureLocation($audit->signatureLocation) : null,
            $audit->drive_folder_id ? new AuditDriveFolderId($audit->drive_folder_id) : null,
            $audit->review_notes ? new AuditReviewNotes($audit->review_notes) : null,
            $audit->auditDecisionMakerId ? new AuditDecisionMakerId($audit->auditDecisionMakerId) : null,
            $audit->auditReviewerId ? new AuditReviewerId($audit->auditReviewerId) : null
        );
    }

    public function update(Audit $audit, SchemeEntityFieldValues $fieldValues = null): void
    {

        $fieldsToSave = [];
        if ($fieldValues) {
            foreach($fieldValues as $fieldValue) {
                $fieldsToSave[$fieldValue->fieldName()->value()] = $fieldValue->value();
            }
        }

        DB::table('audits')
        ->whereRaw('hex(audits.uuid) = "'.$audit->id()->value().'"')
        ->update(
            [
                'audit_type_id' => $audit->auditTypeId()? Uuid2Id::resolve('audit_types', $audit->auditTypeId()): null,
                'real_chief_auditor_id' => $audit->realChiefAuditorId()? Uuid2Id::resolve('auditors', $audit->realChiefAuditorId()): null,
                'straw_chief_auditor_id' => $audit->strawChiefAuditorId() ? Uuid2Id::resolve('auditors', $audit->strawChiefAuditorId()): null,
                'control_level_id' => $audit->controlLevelId() ? Uuid2Id::resolve('audit_control_level_types', $audit->controlLevelId()): null,
                'base_scheme_id' =>  Uuid2Id::resolve('schemes', $audit->baseSchemeId()),
                'scheme_order_id' => $audit->schemeOrderId()? Uuid2Id::resolve('scheme_orders', $audit->schemeOrderId()): null,
                'start_date' => $audit->startDate(),
                'end_date' => $audit->endDate(),
                'workdays' => $audit->workdays(),
                'number' => $audit->number()? $audit->number()->value(): null,
                'code' => $audit->code()? $audit->code()->value(): null,
                'notes' => $audit->notes()? $audit->notes()->value(): null,
                'signature_date' =>  $audit->signatureDate(),
                'signature_location' =>  $audit->signatureLocation() ? $audit->signatureLocation()->value() : null,
                'drive_folder_id' =>  $audit->driveFolderId() ? $audit->driveFolderId()->value() : null,
                'updated_at' => Carbon::now(),
                'is_closed' => $audit->isClosed()->value(),
                'audit_reviewer_id' => $audit->auditReviewerId() ? Uuid2Id::resolve('audit_reviewers', $audit->auditReviewerId()): null,
                'audit_decision_maker_id' => $audit->auditDecisionMakerId() ? Uuid2Id::resolve('audit_decision_makers', $audit->auditDecisionMakerId()): null,
                'review_notes' => $audit->reviewNotes()
            ]  + $fieldsToSave
        );
    }

}
