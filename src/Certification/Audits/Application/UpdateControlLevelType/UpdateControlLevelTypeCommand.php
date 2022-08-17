<?php

namespace Qalis\Certification\Audits\Application\UpdateControlLevelType;

class UpdateControlLevelTypeCommand {
    private $auditId;
    private $controlLevelId;

    public function __construct($auditId, $controlLevelId) {
        $this->auditId = $auditId;
        $this->controlLevelId = $controlLevelId;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function controlLevelId() {
        return $this->controlLevelId;
    }

}
