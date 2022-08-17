<?php

namespace Qalis\Certification\Audits\Application\UpdateDriveFolderId;

class UpdateDriveFolderIdCommand {

    private string $auditId;
    private ?string $driveFolderId;

    public function __construct(string $auditId, ?string $driveFolderId = null) {
        $this->auditId = $auditId;
        $this->driveFolderId = $driveFolderId;
    }

    public function auditId(): string {
        return $this->auditId;
    }

    public function driveFolderId(): ?string {
        return $this->driveFolderId;
    }

}
