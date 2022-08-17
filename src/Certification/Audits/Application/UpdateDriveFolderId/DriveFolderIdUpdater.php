<?php

namespace Qalis\Certification\Audits\Application\UpdateDriveFolderId;

use InvalidArgumentException;
use Qalis\Certification\Audits\Domain\AuditDriveFolderId;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Shared\Domain\DriveDocuments\DriveDocumentsService;

class DriveFolderIdUpdater {

    private AuditRepository $repository;
    private Audit $audit;
    private DriveDocumentsService $driveDocumentsService;

    public function __construct(AuditRepository $repository, DriveDocumentsService $driveDocumentsService)
    {
        $this->repository = $repository;
        $this->driveDocumentsService = $driveDocumentsService;
    }

    public function __invoke(UpdateDriveFolderIdCommand $command) : void
    {
        $this->ensureDestinationFolderExists($command->driveFolderId());
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateDriveFolderId($command->driveFolderId() ? new AuditDriveFolderId($command->driveFolderId()) : null);
        //guardamos
        $this->repository->update($this->audit);
    }

    private function ensureDestinationFolderExists(string $driveFolderId) {
        if (!$this->driveDocumentsService->folderExist($driveFolderId)) {
            throw new InvalidArgumentException("El ID de la carpeta drive introducido no es válido. No existe ninguna carpeta con el id <$driveFolderId>");
        }
    }

}
