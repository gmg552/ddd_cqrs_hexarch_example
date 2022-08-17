<?php

namespace Qalis\Certification\Audits\Application\Delete;

use Qalis\Shared\Domain\Bus\Command\Command;

class DeleteAuditCommand extends Command {

    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function id(): string {
        return $this->id;
    }

}
