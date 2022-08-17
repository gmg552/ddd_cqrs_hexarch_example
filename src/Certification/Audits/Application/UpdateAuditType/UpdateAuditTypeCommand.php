<?php

namespace Qalis\Certification\Audits\Application\UpdateAuditType;

class UpdateAuditTypeCommand {
    private $auditId;
    private $auditTypeId;

    public function __construct($auditId, $auditTypeId) {
        $this->auditId = $auditId;
        $this->auditTypeId = $auditTypeId;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function auditTypeId() {
        return $this->auditTypeId;
    }

}
