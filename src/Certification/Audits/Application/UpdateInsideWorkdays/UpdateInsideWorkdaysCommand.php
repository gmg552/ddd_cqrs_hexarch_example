<?php

namespace Qalis\Certification\Audits\Application\UpdateInsideWorkdays;

class UpdateInsideWorkdaysCommand {
    private $auditId;
    private $insideWorkdays;

    public function __construct($auditId, $insideWorkdays) {
        $this->auditId = $auditId;
        $this->insideWorkdays = $insideWorkdays;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function insideWorkdays() {
        return $this->insideWorkdays;
    }

}