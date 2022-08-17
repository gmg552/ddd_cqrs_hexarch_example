<?php

namespace Qalis\Certification\Audits\Application\UpdateSignatureDate;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;

class SignatureDateUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateSignatureDateCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateSignatureDate(new DateTimeValueObject($command->signatureDate()));
        //guardamos
        $this->repository->update($this->audit);
    }

}
