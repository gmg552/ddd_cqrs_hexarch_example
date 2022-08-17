<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain\Exceptions;

use RuntimeException;

final class InvalidAuditSignatureLocation extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function invalidLength(int $length) : self {
        return new static("La longitud de 'Lugar firma' no puede ser superior a $length");
    }
}
