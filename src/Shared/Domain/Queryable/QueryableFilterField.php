<?php

namespace Qalis\Shared\Domain\Queryable;


class QueryableFilterField extends QueryableFilterOperand{

   
   private string $name;

   public function __construct(string $name)
   {
      $this->name = $name;
   }

   public function name() : string {
      return $this->name;
   }

   
   public function __toString() : string {
      return $this->name;
   }


}