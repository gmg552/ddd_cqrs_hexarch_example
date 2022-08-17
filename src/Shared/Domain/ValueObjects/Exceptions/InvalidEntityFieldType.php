<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects\Exceptions;

use InvalidArgumentException;


class InvalidEntityFieldType extends InvalidArgumentException
{
    public function __construct($value)
    {
        parent::__construct("El valor $value debe de ser tipo INTEGER, BOOL, STRING, DECIMAL O DATE.");
    }
}
