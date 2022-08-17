<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Update;

class UpdateAuditMassBalanceRecordCommandHandler
{
    private AuditMassBalanceRecordUpdater $auditMassBalanceRecordUpdater;

    public function __construct(AuditMassBalanceRecordUpdater $auditMassBalanceRecordUpdater)
    {
        $this->auditMassBalanceRecordUpdater = $auditMassBalanceRecordUpdater;
    }

    public function __invoke(UpdateAuditMassBalanceRecordCommand $command)
    {
        $this->auditMassBalanceRecordUpdater->__invoke($command);
    }

}
