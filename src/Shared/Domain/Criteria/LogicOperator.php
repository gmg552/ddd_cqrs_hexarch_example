<?php

namespace Qalis\Shared\Domain\Criteria;

use InvalidArgumentException;

class LogicOperator extends FilterExpressionItem
{

   public const AND  = 'and';
   public const OR   = 'or';

   private $values = [self::AND, self::OR];

   private string $value;

   public function __construct(string $value)
   {
      $this->ensureIsAValidValue($value);
      $this->value = $value;
   }

   public function value() : string {
      return $this->value;
   }

   private function ensureIsAValidValue(string $value){
      if(!in_array($value, $this->values))
         throw new InvalidArgumentException(sprintf('El operador lógico <%s> para la expresión de filtrado no es válido', $value));
   }

   public function __toString() : string {
      return strtoupper($this->value);
   }

   public function equals(string $anotherOperator){
      return $this->value === $anotherOperator;
   }

}