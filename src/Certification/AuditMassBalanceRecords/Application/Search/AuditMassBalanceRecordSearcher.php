<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Search;

use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordReadRepository;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class AuditMassBalanceRecordSearcher {

    private AuditMassBalanceRecordReadRepository $auditMassBalanceRecordReadRepository ;

    public function __construct(AuditMassBalanceRecordReadRepository $auditMassBalanceRecordReadRepository)
    {
        $this->auditMassBalanceRecordReadRepository = $auditMassBalanceRecordReadRepository;
    }

    public function __invoke(AuditId $id) : AuditMassBalanceRecordsResponse
    {
        return $this->auditMassBalanceRecordReadRepository->searchByAudit($id);
    }

}
