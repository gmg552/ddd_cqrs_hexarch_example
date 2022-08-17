<?php

namespace Qalis\Certification\Audits\Application\UpdateSchemeEntityFields;

use Qalis\Shared\Domain\Bus\Command\Command;

class UpdateAuditSchemeEntityFieldValuesCommand extends Command {

    private string $id;
    private array $setEntityFieldValueCommands;

    public function __construct($id, ...$setEntityFieldValueCommands) {
        $this->id = $id;
        $this->setEntityFieldValueCommands = $setEntityFieldValueCommands;
    }

    public function id(): string {
        return $this->id;
    }

    public function setEntityFieldValueCommands(): array {
        return $this->setEntityFieldValueCommands;
    }

}
