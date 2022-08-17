<?php

declare(strict_types=1);

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Search;

use Qalis\Certification\Shared\Domain\Audits\AuditId;

final class SearchAuditMassBalanceRecordsQueryHandler
{
    private AuditMassBalanceRecordSearcher $auditMassBalanceRecordSearcher;

    public function __construct(AuditMassBalanceRecordSearcher $auditMassBalanceRecordSearcher)
    {
        $this->auditMassBalanceRecordSearcher = $auditMassBalanceRecordSearcher;
    }

    public function __invoke(SearchAuditMassBalanceRecordsQuery $query)
    {
        return $this->auditMassBalanceRecordSearcher->__invoke(new AuditId($query->auditId()));
    }
}
