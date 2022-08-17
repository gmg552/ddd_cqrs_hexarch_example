<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Domain;

use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\SchemeEntityFieldValues;

interface AuditMassBalanceRecordRepository {
    public function save(AuditMassBalanceRecord $auditMassBalanceRecord, SchemeEntityFieldValues $fieldValues) : void;
    public function find(AuditMassBalanceRecordId $id) : AuditMassBalanceRecord;
    public function delete(AuditMassBalanceRecordId $id): void;
}
