<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;


class BoolValueObject
{
   public function __construct(bool $value)
   {
      $this->value = $value;
   }

   public function value(): bool
   {
       return $this->value;
   }
}
