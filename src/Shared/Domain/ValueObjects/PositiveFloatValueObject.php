<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidPositiveFloat;

class PositiveFloatValueObject extends FloatValueObject
{

   public function __construct(float $value)
   {
      $this->ensureIsPositive($value);
      $this->value = $value;
   }

   private function ensureIsPositive(float $value): void
   {
      if ($value < 0)
         throw new InvalidPositiveFloat($value);
   }

   public static function isValid($value) : bool {
      return is_numeric($value) && $value > 0;
   }
}
