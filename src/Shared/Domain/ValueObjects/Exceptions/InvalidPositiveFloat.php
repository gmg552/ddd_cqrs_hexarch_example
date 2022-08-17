<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects\Exceptions;

use InvalidArgumentException;

class InvalidPositiveFloat extends InvalidArgumentException
{
   public function __construct(float $value)
   {
      parent::__construct("El valor debe ser positivo. Se encontró el valor $value");
   }
}