<?php

namespace Qalis\Certification\Audits\Application\UpdateEndDate;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;

class EndDateUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateEndDateCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateEndDate(new DateValueObject($command->endDate()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
