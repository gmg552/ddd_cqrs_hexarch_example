<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Search;

use Qalis\Shared\Domain\Bus\Query\Query;

class SearchAuditMassBalanceRecordsQuery extends Query {

    private string $auditId;

    public function __construct(string $auditId) {
        $this->auditId = $auditId;
    }

    public function auditId(): string {
        return $this->auditId;
    }

}
