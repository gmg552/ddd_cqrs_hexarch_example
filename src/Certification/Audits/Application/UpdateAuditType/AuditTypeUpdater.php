<?php

namespace Qalis\Certification\Audits\Application\UpdateAuditType;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\AuditTypes\AuditTypeId;

class AuditTypeUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateAuditTypeCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateAuditTypeId(new AuditTypeId($command->auditTypeId()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
