<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use RuntimeException;

final class OperatorCBNumerationRangeNotFound extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function invalidOperatorCBNumerationRange(string $operator, string $scheme) : self {
        return new static("No se ha encontrado el rango de numeración para el operador con id $operator para el esquema con id $scheme");
    }

}
