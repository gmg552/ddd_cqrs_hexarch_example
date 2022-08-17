<?php

namespace Qalis\Certification\Audits\Application\UpdateAuditDecisionMaker;

class UpdateAuditDecisionMakerCommand {

    private $auditId;
    private $auditDecisionMakerId;

    public function __construct($auditId, $auditDecisionMakerId) {
        $this->auditId = $auditId;
        $this->auditDecisionMakerId = $auditDecisionMakerId;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function auditDecisionMakerId() {
        return $this->auditDecisionMakerId;
    }

}
