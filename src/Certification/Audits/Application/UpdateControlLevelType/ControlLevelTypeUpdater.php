<?php

namespace Qalis\Certification\Audits\Application\UpdateControlLevelType;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\ControlLevels\ControlLevelId;

class ControlLevelTypeUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateControlLevelTypeCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateControlLevelId(new ControlLevelId($command->controlLevelId()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
