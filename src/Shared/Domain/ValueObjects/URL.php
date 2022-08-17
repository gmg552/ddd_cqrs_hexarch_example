<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use InvalidArgumentException;

class URL extends StringValueObject {

    public function __construct($value = null)
    {
        $this->ensureIsAValidURL($value);
        parent::__construct($value);
    }

    private function ensureIsAValidURL($value){
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("La url <$value> no es válida.");
        }
    }


}
