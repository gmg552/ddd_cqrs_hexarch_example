<?php

namespace Qalis\Certification\Audits\Application\Delete;

class DeleteAuditCommandHandler
{
    private AuditRemover $auditRemover;

    public function __construct(AuditRemover $auditRemover)
    {
        $this->auditRemover = $auditRemover;
    }

    public function __invoke(DeleteAuditCommand $command)
    {
        $this->auditRemover->__invoke($command->id());
    }

}
