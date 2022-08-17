<?php

namespace Qalis\Certification\Audits\Application\UpdateInsideWorkdays;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class InsideWorkdaysUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateInsideWorkdaysCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateInsideWorkdays($command->insideWorkdays());
        //guardamos
        $this->repository->update($this->audit);
    }

}
