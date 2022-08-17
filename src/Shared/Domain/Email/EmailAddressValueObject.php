<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Email;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class EmailAddressValueObject extends StringValueObject {

    public function __construct($value = null)
    {
        $this->ensureIsAValidEmail($value);
        parent::__construct($value);
    }

    private function ensureIsAValidEmail($value){
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El correo electrónico <$value> no se reconoce como válido.");
        }
    }

    public static function isValid(string $email): bool {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}
