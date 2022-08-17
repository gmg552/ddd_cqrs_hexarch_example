<?php

namespace Qalis\Certification\Audits\Application\UpdateNumber;

class UpdateNumberCommand {
    private $auditId;
    private $number;

    public function __construct($auditId, $number) {
        $this->auditId = $auditId;
        $this->number = $number;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function number() {
        return $this->number;
    }

}