<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Domain;

use Qalis\Certification\AuditMassBalanceRecords\Application\Find\FindAuditMassBalanceRecordResponse;
use Qalis\Certification\AuditMassBalanceRecords\Application\Search\AuditMassBalanceRecordsResponse;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldQueryParamsResponse;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;

interface AuditMassBalanceRecordReadRepository {
    public function searchByAudit(AuditId $auditId): AuditMassBalanceRecordsResponse;
    public function find(AuditMassBalanceRecordId $id, SchemeEntityFieldsResponse $fields): FindAuditMassBalanceRecordResponse;
    public function getEntityFieldQueryParams(AuditMassBalanceRecordId $id): SchemeEntityFieldQueryParamsResponse;
}
