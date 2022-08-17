<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidPositiveInt;

class PositiveIntValueObject extends IntValueObject
{
   public const MAX_SIZE_TINYINT = 127;
   public const MAX_SIZE_SMALLINT = 32767;
   public const MAX_SIZE_MEDIUMINT = 8388607;
   public const MAX_SIZE_INT = 2147483647;
   public const MAX_SIZE_BIGINT = 9223372036854775807;

   public function __construct(int $value)
   {
      $this->ensureIsPositive($value);
      parent::__construct($value);
   }

   private function ensureIsPositive(int $value): void
   {
      if ($value < 0)
         throw new InvalidPositiveInt($value);
   }
}
