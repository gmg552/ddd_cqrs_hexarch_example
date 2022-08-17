<?php

namespace Qalis\Certification\Audits\Application\Find;

use Qalis\Shared\Domain\Bus\Query\Query;

class AuditFinderQuery extends Query {

    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function id(): string {
        return $this->id;
    }

}
