<?php

namespace Qalis\Certification\Audits\Application\UpdateOffsiteWorkdays;

class UpdateOffsiteWorkdaysCommand {
    private $auditId;
    private $offsiteWorkdays;

    public function __construct($auditId, $offsiteWorkdays) {
        $this->auditId = $auditId;
        $this->offsiteWorkdays = $offsiteWorkdays;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function offsiteWorkdays() {
        return $this->offsiteWorkdays;
    }

}