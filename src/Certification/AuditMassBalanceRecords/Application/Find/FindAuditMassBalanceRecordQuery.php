<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Find;

use Qalis\Shared\Domain\Bus\Query\Query;

class FindAuditMassBalanceRecordQuery extends Query {

    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function id(): string {
        return $this->id;
    }

}
