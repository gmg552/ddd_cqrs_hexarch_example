<?php

namespace Qalis\Certification\Audits\Application\UpdateReviewNotes;

class UpdateReviewNotesCommand {

    private $auditId;
    private $reviewNotes;

    public function __construct($auditId, $reviewNotes) {
        $this->auditId = $auditId;
        $this->reviewNotes = $reviewNotes;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function reviewNotes() {
        return $this->reviewNotes;
    }

}
