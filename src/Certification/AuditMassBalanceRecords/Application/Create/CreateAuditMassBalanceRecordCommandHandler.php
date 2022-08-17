<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Create;

use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Products\ProductId;

class CreateAuditMassBalanceRecordCommandHandler
{
    private AuditMassBalanceRecordCreator $auditMassBalanceRecordCreator;

    public function __construct(AuditMassBalanceRecordCreator $auditMassBalanceRecordCreator)
    {
        $this->auditMassBalanceRecordCreator = $auditMassBalanceRecordCreator;
    }

    public function __invoke(CreateAuditMassBalanceRecordCommand $command)
    {
        $this->auditMassBalanceRecordCreator->__invoke(
            new AuditMassBalanceRecordId($command->id()),
            new ProductId($command->productId()),
            new AuditId($command->auditId()),
            ...$command->setSchemeEntityFieldValueCommand()
        );
    }

}
