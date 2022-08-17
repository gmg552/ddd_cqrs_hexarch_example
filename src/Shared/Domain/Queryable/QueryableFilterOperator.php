<?php

namespace Qalis\Shared\Domain\Queryable;

use InvalidArgumentException;

class QueryableFilterOperator {
   public const EQUAL        = '=';
   public const NOT_EQUAL    = '!=';
   public const GT           = '>';
   public const LT           = '<';
   public const CONTAINS     = 'CONTAINS';
   public const NOT_CONTAINS = 'NOT_CONTAINS';
   public const GET           = '>=';
   public const LET           = '<=';
   private $values = [self::EQUAL, self::NOT_EQUAL, self::GT,  self::LT, self::CONTAINS, self::NOT_CONTAINS, self::GET, self::LET];

   private string $value;

   public static function equal() : self {
      return new self(self::EQUAL);
   }

   public static function notEqual() : self {
      return new self(self::NOT_EQUAL);
   }

   public static function greaterThan() : self {
      return new self(self::GT);
   }

   public static function greaterThanOrEqual() : self {
      return new self(self::GET);
   }

   public static function lowerThan() : self {
      return new self(self::LT);
   }

   public static function lowerThanOrEqual() : self {
      return new self(self::LET);
   }

   public static function contains() : self {
      return new self(self::CONTAINS);
   }

   public static function notContains() : self {
      return new self(self::NOT_CONTAINS);
   }

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
         throw new InvalidArgumentException(sprintf('El operador de filtro <%s> no es válido', $value));
   }

   public function __toString() : string {
      return strtoupper($this->value);
   }
}