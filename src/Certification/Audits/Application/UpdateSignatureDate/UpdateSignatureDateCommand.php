<?php

namespace Qalis\Certification\Audits\Application\UpdateSignatureDate;

class UpdateSignatureDateCommand {
    private $auditId;
    private $signatureDate;

    public function __construct($auditId, $signatureDate) {
        $this->auditId = $auditId;
        $this->signatureDate = $signatureDate;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function signatureDate() {
        return $this->signatureDate;
    }

}
