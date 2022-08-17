<?php

namespace Qalis\Certification\Audits\Application\UpdateNotes;

class UpdateNotesCommand {
    private string $auditId;
    private ?string $notes;

    public function __construct(string $auditId, ?string $notes = null) {
        $this->auditId = $auditId;
        $this->notes = $notes;
    }

    public function auditId() {
        return $this->auditId;
    }

    public function notes() {
        return $this->notes;
    }

}
