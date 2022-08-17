<?php

namespace Qalis\Shared\Domain\Offers;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class OfferState extends StringValueObject
{

    public const PENDING = 'pending';
    public const SENT = 'sent';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        parent::__construct($value);
    }

    private function ensureIsValid(string $value) {
        if (!self::isValid($value)) {
            throw new InvalidArgumentException("El estado de una oferta económica debe de ser 'Pendiente', 'Enviada', 'Rechazada' ó 'Aceptada'. Se ha encontrado '$value'.");
        }
    }

    public static function isValid(string $value) : bool {
        return $value == self::PENDING || $value == self::SENT || $value == self::ACCEPTED || $value == self::REJECTED;
    }

}
