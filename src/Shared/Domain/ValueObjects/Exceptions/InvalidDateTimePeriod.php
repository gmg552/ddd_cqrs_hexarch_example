<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects\Exceptions;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;

class InvalidDateTimePeriod extends InvalidArgumentException
{
   public function __construct(DateTimeValueObject $start, DateTimeValueObject $end)
   {
      parent::__construct(
         sprintf('La fecha de inicio <%s> no puede ser posterior a la de fin <%s>', 
         $start->__toString('d-m-Y H:i:s'), 
         $end->__toString('d-m-Y H:i:s'))
      );
   }
}