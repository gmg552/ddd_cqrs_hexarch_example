<?php

namespace Qalis\Shared\Domain\Criteria;

class OrderField
{

   private string $entity;
   private string $field;

   public function __construct(string $entity, string $field)
   {
      $this->entity = $entity;
      $this->field = $field;
   }

   public function entity() : string {
      return $this->entity;
   }

   public function field() : string {
      return $this->field;
   }

   public function value() : string {
      return  $this->entity. '.' . $this->field;
   }

   public function __toString() : string {
      return $this->entity.'.'.$this->field;
   }


}