<?php

namespace Qalis\Certification\Audits\Application\Create;

final class CreateAuditCommandHandler {

    private AuditCreator $auditCreator;

    public function __construct(AuditCreator $auditCreator)
    {
        $this->auditCreator = $auditCreator;
    }

    public function __invoke(CreateAuditCommand $command)
    {
        $this->auditCreator->__invoke(
            $command->id(),
            $command->operatorId(),
            $command->auditSchemeId()
        );
    }

}
