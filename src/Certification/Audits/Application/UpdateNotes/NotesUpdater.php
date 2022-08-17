<?php

namespace Qalis\Certification\Audits\Application\UpdateNotes;

use Qalis\Certification\Audits\Domain\AuditNotes;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class NotesUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateNotesCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateNotes($command->notes() ? new AuditNotes($command->notes()) : null);
        //guardamos
        $this->repository->update($this->audit);
    }

}
