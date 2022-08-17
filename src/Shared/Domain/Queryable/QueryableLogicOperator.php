<?php

namespace Qalis\Shared\Domain\Queryable;

use InvalidArgumentException;

class QueryableLogicOperator extends QueryableExpressionItem{
   public const AND  = 'and';
   public const OR   = 'or';
   public const NOT  = 'not';

   private $values = [self::AND, self::OR, self::NOT];

   private string $value;

   public function __construct(string $value)
   {
      $this->ensureIsAValidValue($value);
      $this->value = $value;
   }

   public static function and() : self {
      return new self(self::AND);
   }

   public static function or() : self {
      return new self(self::OR);
   }

   public static function not() : self {
      return new self(self::NOT);
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