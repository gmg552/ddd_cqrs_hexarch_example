<?php

namespace Qalis\Certification\Audits\Application\UpdateStartDate;

class UpdateStartDateCommand {
    private $auditId;
    private $startDate;

    public function __construct($auditId, $startDate) {
        $this->auditId = $auditId;
        $this->startDate = $startDate;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function startDate() {
        return $this->startDate;
    }

}