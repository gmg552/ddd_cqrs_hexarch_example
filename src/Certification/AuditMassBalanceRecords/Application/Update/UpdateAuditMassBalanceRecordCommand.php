<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Update;


use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set\SetSchemeEntityFieldValueCommand;
use Qalis\Shared\Domain\Bus\Command\Command;

class UpdateAuditMassBalanceRecordCommand extends Command
{
    private string $id;
    private string $productId;
    private array $setEntityFieldValueCommands;

    public function __construct(
        string $id,
        string $productId,
        SetSchemeEntityFieldValueCommand ...$setEntityFieldValueCommands
    )
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->setEntityFieldValueCommands = $setEntityFieldValueCommands;
    }

    public function id(): string {
        return $this->id;
    }

    public function productId(): string {
        return $this->productId;
    }

    public function setEntityFieldValueCommands(): array {
        return $this->setEntityFieldValueCommands;
    }

}
