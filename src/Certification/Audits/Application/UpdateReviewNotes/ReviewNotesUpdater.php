<?php

namespace Qalis\Certification\Audits\Application\UpdateReviewNotes;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditReviewNotes;
use Qalis\Certification\Shared\Domain\AuditReviewers\AuditReviewerId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class ReviewNotesUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateReviewNotesCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateReviewNotes(new AuditReviewNotes($command->reviewNotes()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
