<?php

namespace Qalis\Certification\Audits\Application\UpdateAuditReviewer;

class UpdateAuditReviewerCommand {

    private $auditId;
    private $auditReviewerId;

    public function __construct($auditId, $auditReviewerId) {
        $this->auditId = $auditId;
        $this->auditReviewerId = $auditReviewerId;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function auditReviewerId() {
        return $this->auditReviewerId;
    }

}
