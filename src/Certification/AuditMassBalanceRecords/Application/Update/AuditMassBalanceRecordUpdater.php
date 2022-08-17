<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Update;

use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordRepository;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Products\ProductId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\Search\SchemeEntityFieldValueSearcher;
use Qalis\Shared\Domain\Bus\Event\EventBus;

class AuditMassBalanceRecordUpdater {

    private AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository;
    private SchemeEntityFieldValueSearcher $schemeEntityFieldValueSearcher;
    private EventBus $bus;


    public function __construct(AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository, SchemeEntityFieldReadRepository $schemeEntityFieldRepository, EventBus $bus)
    {
        $this->auditMassBalanceRecordRepository = $auditMassBalanceRecordRepository;
        $this->bus = $bus;
        $this->schemeEntityFieldValueSearcher = new SchemeEntityFieldValueSearcher($schemeEntityFieldRepository);
    }

    public function __invoke(UpdateAuditMassBalanceRecordCommand $command) : void
    {
        $auditMassBalanceRecord = $this->auditMassBalanceRecordRepository->find(new AuditMassBalanceRecordId($command->id()));

        $fieldValues = $this->getEntityFieldValues($command->setEntityFieldValueCommands());

        $auditMassBalanceRecord->updateProductId(new ProductId($command->productId()));

        $this->auditMassBalanceRecordRepository->save($auditMassBalanceRecord, $fieldValues);

    }

    private function getEntityFieldValues(array $setSchemeEntityFieldValueCommands){
        return $this->schemeEntityFieldValueSearcher->__invoke(...$setSchemeEntityFieldValueCommands);
    }

}
