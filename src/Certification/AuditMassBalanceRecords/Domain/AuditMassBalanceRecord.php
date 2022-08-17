<?php

declare(strict_types=1);

namespace Qalis\Certification\AuditMassBalanceRecords\Domain;

use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Products\ProductId;

class AuditMassBalanceRecord
{
    private AuditMassBalanceRecordId $id;
    private ProductId $productId;
    private AuditId $auditId;

    public const CODE = 'audit_mass_balance_record';

    public function __construct(
        AuditMassBalanceRecordId $id,
        ProductId $productId,
        AuditId $auditId
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->auditId = $auditId;
    }

    public function id(): AuditMassBalanceRecordId {
        return $this->id;
    }

    public function productId(): ProductId {
        return $this->productId;
    }

    public function auditId(): AuditId {
        return $this->auditId;
    }

    public function updateProductId(ProductId $productId): void {
        $this->productId = $productId;
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'auditId' => $this->auditId
        ];
    }

}
