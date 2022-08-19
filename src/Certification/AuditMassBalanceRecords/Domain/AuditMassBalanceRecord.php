<?php

declare(strict_types=1);

namespace Qalis\Certification\AuditMassBalanceRecords\Domain;

use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Products\ProductId;


/**
 * Audit mass balance record
 * @author Guillermo Martínez García <gmg552@gmail.com>
 * @access public
 */
class AuditMassBalanceRecord
{
    private AuditMassBalanceRecordId $id;
    private ProductId $productId;
    private AuditId $auditId;

    /**
     * Entity code
     */
    public const CODE = 'audit_mass_balance_record';

    /**
     * Constructor
     *
     * @param AuditMassBalanceRecordId $id Id
     * @param ProductId $productId Related product
     * @param AuditId $auditId Related audit
     */
    public function __construct(
        AuditMassBalanceRecordId $id,
        ProductId $productId,
        AuditId $auditId
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->auditId = $auditId;
    }

    /**
     * Get id
     *
     * @return AuditMassBalanceRecordId
     */
    public function id(): AuditMassBalanceRecordId {
        return $this->id;
    }

    /**
     * Get product
     *
     * @return ProductId
     */
    public function productId(): ProductId {
        return $this->productId;
    }

    /**
     * Get audit
     *
     * @return AuditId
     */
    public function auditId(): AuditId {
        return $this->auditId;
    }

    /**
     * Update product
     *
     * @param ProductId $productId
     * @return void
     */
    public function updateProductId(ProductId $productId): void {
        $this->productId = $productId;
    }

    /**
     * Return this audit mass balance record as array of primitives
     *
     * @return array
     */
    public function toArray() : array {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'auditId' => $this->auditId
        ];
    }

}
