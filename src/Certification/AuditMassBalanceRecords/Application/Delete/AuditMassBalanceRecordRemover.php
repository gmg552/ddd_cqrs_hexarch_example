<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Delete;

use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordRepository;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;

class AuditMassBalanceRecordRemover {

    private AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository;

    public function __construct(
        AuditMassBalanceRecordRepository $auditMassBalanceRecordRepository
    )
    {
        $this->auditMassBalanceRecordRepository = $auditMassBalanceRecordRepository;
    }

    public function __invoke(string $id) : void
    {
        $this->auditMassBalanceRecordRepository->delete(new AuditMassBalanceRecordId($id));
    }


}
