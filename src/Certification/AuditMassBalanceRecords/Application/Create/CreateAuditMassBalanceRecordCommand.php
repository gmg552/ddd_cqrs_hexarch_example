<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Create;

use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set\SetSchemeEntityFieldValueCommand;
use Qalis\Shared\Domain\Bus\Command\Command;

class CreateAuditMassBalanceRecordCommand extends Command
{
    private string $id;
    private string $auditId;
    private string $productId;
    private array $setSchemeEntityFieldValueCommand;

    public function __construct(
        string $id,
        string $auditId,
        string $productId,
        SetSchemeEntityFieldValueCommand ...$setSchemeEntityFieldValueCommand
    )
    {
        $this->id = $id;
        $this->auditId = $auditId;
        $this->productId = $productId;
        $this->setSchemeEntityFieldValueCommand = $setSchemeEntityFieldValueCommand;
    }

    public function id(): string {
        return $this->id;
    }

    public function auditId(): string {
        return $this->auditId;
    }

    public function productId(): string {
        return $this->productId;
    }

    public function setSchemeEntityFieldValueCommand(): array {
        return $this->setSchemeEntityFieldValueCommand;
    }

}
