<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects\Exceptions;

use InvalidArgumentException;


class InvalidUuid extends InvalidArgumentException
{
   public function __construct($uuid, $class)
   {
      parent::__construct("El identificador $uuid debe tener longitud 32 y ser hexadecimal al construir <$class>");
   }
}