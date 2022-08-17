<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Infrastructure\Persistence;

use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecord;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordRepository;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Products\ProductId;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\SchemeEntityFieldValues;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\AuditMassBalanceRecord as AuditMassBalanceRecordEloquent;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

class LaravelAuditMassBalanceRecordRepository implements AuditMassBalanceRecordRepository {

    public function find(AuditMassBalanceRecordId $id): AuditMassBalanceRecord
    {
        $auditMassBalanceRecord = AuditMassBalanceRecordEloquent::with('product')->whereUuid($id->value())->first();

        return new AuditMassBalanceRecord(
            new AuditMassBalanceRecordId($auditMassBalanceRecord->uuid),
            new ProductId($auditMassBalanceRecord->product->uuid),
            new AuditId($auditMassBalanceRecord->audit->uuid)
        );
    }

    public function save(AuditMassBalanceRecord $auditMassBalanceRecord, SchemeEntityFieldValues $fieldValues): void
    {
        $fieldsToSave = [];
        foreach($fieldValues as $fieldValue) {
            $fieldsToSave[$fieldValue->fieldName()->value()] = $fieldValue->value();
        }

        AuditMassBalanceRecordEloquent::updateOrCreate(
            [
                'uuid' => $auditMassBalanceRecord->id()->binValue()
            ],
            [
                'product_id' => Uuid2Id::resolve('products', $auditMassBalanceRecord->productId()->value()),
                'audit_id' => Uuid2Id::resolve('audits', $auditMassBalanceRecord->auditId()->value())
            ] + $fieldsToSave
        );
    }

    public function delete(AuditMassBalanceRecordId $id): void
    {
        $auditMassBalanceRecord = AuditMassBalanceRecordEloquent::whereUuid($id->value())->first();
        $auditMassBalanceRecord->forceDelete();
    }
}
