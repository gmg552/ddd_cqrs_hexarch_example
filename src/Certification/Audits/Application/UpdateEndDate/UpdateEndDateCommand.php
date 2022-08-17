<?php

namespace Qalis\Certification\Audits\Application\UpdateEndDate;

class UpdateEndDateCommand {
    private $auditId;
    private $endDate;

    public function __construct($auditId, $endDate) {
        $this->auditId = $auditId;
        $this->endDate = $endDate;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function endDate() {
        return $this->endDate;
    }

}