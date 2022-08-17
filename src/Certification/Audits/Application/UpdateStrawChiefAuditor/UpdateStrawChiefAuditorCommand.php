<?php

namespace Qalis\Certification\Audits\Application\UpdateStrawChiefAuditor;

class UpdateStrawChiefAuditorCommand {
    private $auditId;
    private $strawChiefAuditorId;

    public function __construct($auditId, $strawChiefAuditorId) {
        $this->auditId = $auditId;
        $this->strawChiefAuditorId = $strawChiefAuditorId;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function strawChiefAuditorId() {
        return $this->strawChiefAuditorId;
    }

}
