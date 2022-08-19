<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Create;

use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set\SetSchemeEntityFieldValueCommand;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecord;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordRepository;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Products\ProductId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\Search\SchemeEntityFieldValueSearcher;
use Qalis\Certification\Shared\Infrastructure\Persistence\SchemeEntityFields\LaravelSchemeEntityFieldReadRepository;

/**
 * Use case: create a new audit mass balance record
 * @author Guillermo Martínez García <gmg552@gmail.com>
 * @access public
 */
class AuditMassBalanceRecordCreator {

    private AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository;
    private SchemeEntityFieldValueSearcher $schemeEntityFieldValueSearcher;

    /**
     * Constructor
     *
     * @param AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository Repository for audit mass balance records
     */
    public function __construct(AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository)
    {
        $this->auditMassBalanceRecordRepository = $auditMassBalanceRecordRepository;
        $this->schemeEntityFieldValueSearcher = new SchemeEntityFieldValueSearcher(new LaravelSchemeEntityFieldReadRepository());
    }

    /**
     * Invoke the use case
     *
     * @param AuditMassBalanceRecordId $id Id of the new record
     * @param ProductId $productId Related product
     * @param AuditId $auditId Related audit
     * @param SetSchemeEntityFieldValueCommand ...$setSchemeEntityFieldValueCommand Entity fields
     * @return void
     */
    public function __invoke(AuditMassBalanceRecordId $id, ProductId $productId, AuditId $auditId, SetSchemeEntityFieldValueCommand ...$setSchemeEntityFieldValueCommand) : void
    {
        $auditMassBalanceRecord = new AuditMassBalanceRecord(
            $id, $productId, $auditId
        );

        $fieldValues = $this->schemeEntityFieldValueSearcher->__invoke(...$setSchemeEntityFieldValueCommand);

        $this->auditMassBalanceRecordRepository->save($auditMassBalanceRecord, $fieldValues);
    }

}
