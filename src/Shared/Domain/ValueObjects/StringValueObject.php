<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

abstract class StringValueObject
{
    protected string $value;
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString() : string {
        return $this->value;
    }

    public static function isEmpty(string $value = null) : bool {
        return $value == null || trim($value) == '';
    }
}
