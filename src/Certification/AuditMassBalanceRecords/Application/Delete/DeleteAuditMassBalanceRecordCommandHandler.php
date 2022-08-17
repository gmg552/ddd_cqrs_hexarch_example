<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Delete;

class DeleteAuditMassBalanceRecordCommandHandler
{
    private AuditMassBalanceRecordRemover $auditMassBalanceRecordRemover;

    public function __construct(AuditMassBalanceRecordRemover $auditMassBalanceRecordRemover)
    {
        $this->auditMassBalanceRecordRemover = $auditMassBalanceRecordRemover;
    }

    public function __invoke(DeleteAuditMassBalanceRecordCommand $command)
    {
        $this->auditMassBalanceRecordRemover->__invoke($command->id());
    }

}
