<?php

namespace Qalis\Certification\Audits\Application\UpdateStrawChiefAuditor;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Auditors\AuditorId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class StrawChiefAuditorUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateStrawChiefAuditorCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateStrawChiefAuditorId(new AuditorId($command->strawChiefAuditorId()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
