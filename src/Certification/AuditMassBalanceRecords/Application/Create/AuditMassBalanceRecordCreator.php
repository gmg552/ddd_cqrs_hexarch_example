<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Create;

use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set\SetSchemeEntityFieldValueCommand;
use function Lambdish\Phunctional\map;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecord;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordRepository;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Products\ProductId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\Search\SchemeEntityFieldValueSearcher;
use Qalis\Certification\Shared\Infrastructure\Persistence\SchemeEntityFields\LaravelSchemeEntityFieldReadRepository;

class AuditMassBalanceRecordCreator {

    private AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository;
    private SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository;
    private SchemeEntityFieldValueSearcher $schemeEntityFieldValueSearcher;

    public function __construct(
        AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository,
        SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository)
    {
        $this->auditMassBalanceRecordRepository = $auditMassBalanceRecordRepository;
        $this->schemeEntityFieldReadRepository = $schemeEntityFieldReadRepository;
        $this->schemeEntityFieldValueSearcher = new SchemeEntityFieldValueSearcher(new LaravelSchemeEntityFieldReadRepository());
    }

    public function __invoke(AuditMassBalanceRecordId $id, ProductId $productId, AuditId $auditId, SetSchemeEntityFieldValueCommand ...$setSchemeEntityFieldValueCommand) : void
    {
        $auditMassBalanceRecord = new AuditMassBalanceRecord(
            $id, $productId, $auditId
        );

        $fieldValues = $this->schemeEntityFieldValueSearcher->__invoke(...$setSchemeEntityFieldValueCommand);

        $this->auditMassBalanceRecordRepository->save($auditMassBalanceRecord, $fieldValues);
    }

}
