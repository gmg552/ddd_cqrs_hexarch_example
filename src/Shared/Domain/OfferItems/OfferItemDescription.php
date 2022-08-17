<?php

namespace Qalis\Shared\Domain\OfferItems;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class OfferItemDescription extends StringValueObject
{
    public const MAX_LENGTH = 100;

    public function __construct(string $value)
    {
        $this->ensureLengthIsLowerThanLimit($value);
        parent::__construct($value);
    }

    private function ensureLengthIsLowerThanLimit(string $value): void
    {
        if (strlen($value) > self::MAX_LENGTH)
            throw new InvalidArgumentException("La longitud de la descripción del item de la oferta no puede ser superior a ".self::MAX_LENGTH." caracteres.");
    }
}
