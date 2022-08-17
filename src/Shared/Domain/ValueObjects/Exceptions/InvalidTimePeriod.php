<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects\Exceptions;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\TimeValueObject;

class InvalidTimePeriod extends InvalidArgumentException
{
   //sprintf('La fecha de inicio <%s> no puede ser posterior a la de fin <%s>', $start, $end)
   public function __construct(string $message)
   {
      parent::__construct($message);
   }

   public static function incosistent(TimeValueObject $start, TimeValueObject $end) : InvalidTimePeriod{
      return new InvalidTimePeriod(sprintf('La fecha de inicio <%s> no puede ser posterior a la de fin <%s>', $start, $end));
   }

   public static function incomplete() : InvalidTimePeriod {
      return new InvalidTimePeriod('Falta la hora de inicio del periodo');
   }
}