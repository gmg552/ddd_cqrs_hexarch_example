<?php

namespace Qalis\Shared\Domain\Criteria;

class FilterValue extends FilterOperand
{

   private string $value;

   public function __construct(string $value)
   {
      $this->value = $value;
   }

   public function value() : string {
      return $this->value;
   }

   public function __toString() : string
   {
      return $this->value;
   }

}