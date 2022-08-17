<?php

namespace Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set;

use Qalis\Shared\Domain\Bus\Command\Command;

class SetSchemeEntityFieldValueCommand extends Command {

    private string $id;
    private $value;

    public function __construct(
        string $id,
        $value
    ){
        $this->id = $id;
        $this->value = $value;
    }

    public function id(): string {
        return $this->id;
    }

    public function value() {
        return $this->value;
    }



}
