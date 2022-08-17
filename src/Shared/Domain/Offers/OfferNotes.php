<?php

namespace Qalis\Shared\Domain\Offers;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class OfferNotes extends StringValueObject
{
    public const MAX_LENGTH = 500;

    public function __construct(string $value)
    {
        $this->ensureLengthIsLowerThanLimit($value);
        parent::__construct($value);
    }

    private function ensureLengthIsLowerThanLimit(string $value): void
    {
        if (strlen($value) > self::MAX_LENGTH)
            throw new InvalidArgumentException("La longitud de las notas de la oferta económica no puede ser superior a ".self::MAX_LENGTH." caracteres.");
    }
}
