<?php

namespace Qalis\Certification\Audits\Application\UpdateAuditReviewer;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\AuditReviewers\AuditReviewerId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class AuditReviewerUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateAuditReviewerCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateAuditReviewerId(new AuditReviewerId($command->auditReviewerId()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
