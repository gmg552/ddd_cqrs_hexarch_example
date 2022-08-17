<?php

namespace Qalis\Shared\Domain\Criteria;

use InvalidArgumentException;

class Order
{

   public const ASC  = 'asc';
   public const DESC   = 'desc';

   private $orderTypes = [self::ASC, self::DESC];

   private string $orderType;
   private OrderField $orderField;

   public function __construct(OrderField $field, string $orderType)
   {
      $this->ensureIsAValidType($orderType);
      $this->orderType = $orderType;
      $this->orderField = $field;
   }

   public function type() : string {
      return $this->orderType;
   }

   public function field() : OrderField{
      return $this->orderField;
   }

   private function ensureIsAValidType(string $orderType){
      if(!in_array($orderType, $this->orderTypes))
         throw new InvalidArgumentException(sprintf('El orden <%s> para el criterio de búsqueda no es válido', $orderType));
   }

   public function __toString() : string {
      return sprintf('%s %s', $this->orderField, strtoupper($this->orderType));
   }

}