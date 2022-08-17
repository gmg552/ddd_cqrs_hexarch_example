<?php

declare(strict_types=1);

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Find;

use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;

final class FindAuditMassBalanceRecordQueryHandler
{
    private AuditMassBalanceRecordFinder $auditMassBalanceRecordFinder;

    public function __construct(AuditMassBalanceRecordFinder $auditMassBalanceRecordFinder)
    {
        $this->auditMassBalanceRecordFinder = $auditMassBalanceRecordFinder;
    }

    public function __invoke(FindAuditMassBalanceRecordQuery $query)
    {
        return $this->auditMassBalanceRecordFinder->__invoke(new AuditMassBalanceRecordId($query->id()));
    }
}

