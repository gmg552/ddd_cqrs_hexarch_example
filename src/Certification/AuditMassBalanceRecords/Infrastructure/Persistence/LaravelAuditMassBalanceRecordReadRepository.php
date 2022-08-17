<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use function Lambdish\Phunctional\map;
use Qalis\Certification\AuditMassBalanceRecords\Application\Find\FindAuditMassBalanceRecordResponse;
use Qalis\Certification\AuditMassBalanceRecords\Application\Search\AuditMassBalanceRecordResponse;
use Qalis\Certification\AuditMassBalanceRecords\Application\Search\AuditMassBalanceRecordsResponse;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordReadRepository;
use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Search\SchemeEntityFieldValueResponse;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldQueryParamsResponse;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\AuditMassBalanceRecord;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

class LaravelAuditMassBalanceRecordReadRepository implements AuditMassBalanceRecordReadRepository {



    public function searchByAudit(AuditId $auditId): AuditMassBalanceRecordsResponse
    {
        $auditId = Uuid2Id::resolve('audits', $auditId->value());
        $auditMassBalanceRecords = AuditMassBalanceRecord::where('audit_id', $auditId)->get();

        return new AuditMassBalanceRecordsResponse(...map($this->toResponse(), $auditMassBalanceRecords));

    }

    private function toResponse(): callable
    {
        return static fn(AuditMassBalanceRecord $auditMassBalanceRecord) => new AuditMassBalanceRecordResponse(
            $auditMassBalanceRecord->uuid,
            $auditMassBalanceRecord->product ? $auditMassBalanceRecord->product->name : null
        );
    }

    public function find(AuditMassBalanceRecordId $id, SchemeEntityFieldsResponse $fields): FindAuditMassBalanceRecordResponse
    {
        $auditMassBalanceRecord = AuditMassBalanceRecord::with('product')->whereUuid($id->value())->first();

        $entityFieldsValueResponse = [];
        foreach($fields as $field) {
            array_push($entityFieldsValueResponse, new SchemeEntityFieldValueResponse(
                $field->id(),
                $field->label(),
                $field->type(),
                $field->name(),
                $field->stringValues(),
                $auditMassBalanceRecord->{$field->name()}
            ));
        }

        return new FindAuditMassBalanceRecordResponse(
            $auditMassBalanceRecord->uuid,
            $auditMassBalanceRecord->product->uuid,
            $entityFieldsValueResponse
        );
    }

    public function getEntityFieldQueryParams(AuditMassBalanceRecordId $id): SchemeEntityFieldQueryParamsResponse
    {
        $auditData = QueryBuilderUtils::notDeleted(DB::table('audits')
            ->join('audit_mass_balance_records', 'audits.id', '=', 'audit_mass_balance_records.audit_id')
            ->join('schemes', 'schemes.id', '=', 'audits.base_scheme_id'))
            ->whereRaw('hex(audit_mass_balance_records.uuid) = "'.$id->value().'"')
            ->selectRaw('audits.start_date, lower(hex(schemes.uuid)) as baseSchemeId')
            ->first();

        return new SchemeEntityFieldQueryParamsResponse(
            $auditData->start_date,
            [$auditData->baseSchemeId]
        );
    }
}
