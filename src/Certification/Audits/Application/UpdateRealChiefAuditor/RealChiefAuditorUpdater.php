<?php

namespace Qalis\Certification\Audits\Application\UpdateRealChiefAuditor;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Auditors\AuditorId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class RealChiefAuditorUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateRealChiefAuditorCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //validaciones

        //hacemos el set
        $this->audit->updateRealChiefAuditorId(new AuditorId($command->realChiefAuditorId()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
