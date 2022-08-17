<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts;

use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class BatchCommandAttemptState extends StringValueObject
{

    public const PROCESSING = 'processing';
    public const SUCCESSFUL = 'successful';
    public const FAILED = 'failed';

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    private function ensureIsValid(string $value): void
    {
        if (($value !== self::PROCESSING) && ($value !== self::SUCCESSFUL) && ($value !== self::FAILED))
            throw new \InvalidArgumentException("El valor <$value> no se encuentra dentro de los valores permitidos para BatchCommandAttemptState");
    }

}
