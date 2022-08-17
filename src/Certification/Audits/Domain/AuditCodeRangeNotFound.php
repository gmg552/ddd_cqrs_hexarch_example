<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use RuntimeException;

final class AuditCodeRangeNotFound extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function invalidAuditCodeRange() : self {
        return new static("No se encuentra el rango de codificación de auditoría");
    }

}
