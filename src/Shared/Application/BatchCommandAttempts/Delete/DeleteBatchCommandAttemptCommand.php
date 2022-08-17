<?php

namespace Qalis\Shared\Application\BatchCommandAttempts\Delete;

use Qalis\Shared\Domain\Bus\Command\Command;

class DeleteBatchCommandAttemptCommand extends Command
{
    private string $id;

    public function __construct(
        string $id
    )
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

}
