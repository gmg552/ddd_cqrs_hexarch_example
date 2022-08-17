<?php

namespace Qalis\Certification\Audits\Application\UpdateSignatureLocation;

use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditSignatureLocation;
use Qalis\Certification\Shared\Domain\Audits\AuditId;

class SignatureLocationUpdater {

    private AuditRepository $repository;
    private Audit $audit;

    public function __construct(AuditRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateSignatureLocationCommand $command) : void
    {
        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));
        //hacemos el set
        $this->audit->updateSignatureLocation($command->signatureLocation() ? new AuditSignatureLocation($command->signatureLocation()) : null);
        //guardamos
        $this->repository->update($this->audit);
    }

}
