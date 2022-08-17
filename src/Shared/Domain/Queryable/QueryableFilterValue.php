<?php

namespace Qalis\Shared\Domain\Queryable;

use DateTime;
use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;

class QueryableFilterValue extends QueryableFilterOperand {

   private $value;

   public function __construct($value)
   {
      $this->ensureIsAValidType($value);
      $this->value = $value;
   }

   public function value() {
      return $this->value;
   }

   private function ensureIsAValidType($value){
      $type = gettype($value);
      if(!($type == 'boolean' || $type == 'integer' || $type == 'double' || $type == 'string' || $type == 'NULL'))
         throw new InvalidArgumentException("El valor <$type> de QueryableFilterValue no es un tipo váido");
   }

   public function __toString() : string
   {
      return $this->value;
   }
}