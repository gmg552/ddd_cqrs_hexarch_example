<?php

namespace Qalis\Certification\Audits\Application\UpdateRealChiefAuditor;

class UpdateRealChiefAuditorCommand {
    private $auditId;
    private $realChiefAuditorId;

    public function __construct($auditId, $realChiefAuditorId) {
        $this->auditId = $auditId;
        $this->realChiefAuditorId = $realChiefAuditorId;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function realChiefAuditorId() {
        return $this->realChiefAuditorId;
    }

}
