<?php

namespace Qalis\Certification\Audits\Application\UpdateSchemeEntityFields;

use Qalis\Certification\Shared\Domain\Audits\AuditId;

class UpdateAuditSchemeEntityFieldValuesCommandHandler
{
    private AuditSchemeEntityFieldsUpdater $auditSchemeEntityFieldsUpdater;

    public function __construct(AuditSchemeEntityFieldsUpdater $auditSchemeEntityFieldsUpdater)
    {
        $this->auditSchemeEntityFieldsUpdater = $auditSchemeEntityFieldsUpdater;
    }

    public function __invoke(UpdateAuditSchemeEntityFieldValuesCommand $command)
    {
        $this->auditSchemeEntityFieldsUpdater->__invoke(new AuditId($command->id()), ...$command->setEntityFieldValueCommands());
    }
}
