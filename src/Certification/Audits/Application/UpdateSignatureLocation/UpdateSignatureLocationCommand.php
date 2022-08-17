<?php

namespace Qalis\Certification\Audits\Application\UpdateSignatureLocation;

class UpdateSignatureLocationCommand {
    private $auditId;
    private $signatureLocation;

    public function __construct($auditId, $signatureLocation) {
        $this->auditId = $auditId;
        $this->signatureLocation = $signatureLocation;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function signatureLocation() {
        return $this->signatureLocation;
    }

}
