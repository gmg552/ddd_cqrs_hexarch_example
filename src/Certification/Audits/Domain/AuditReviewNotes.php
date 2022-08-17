<?php

namespace Qalis\Certification\Audits\Domain;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class AuditReviewNotes extends StringValueObject
{
    public const MAX_LENGTH = 600;

    public function __construct(string $value)
    {
        $this->ensureLengthIsLowerThanLimit($value);
        parent::__construct($value);
    }

    private function ensureLengthIsLowerThanLimit(string $value): void
    {
        if (strlen($value) > self::MAX_LENGTH)
            throw new InvalidArgumentException("La longitud de las notas de revisión para auditoría no puede ser superior a ".self::MAX_LENGTH." caracteres.");
    }
}
