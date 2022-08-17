<?php

namespace Qalis\Shared\Domain\Criteria;

use InvalidArgumentException;

abstract class CriteriaField
{

   protected string $entity;
   protected string $field;
   protected ?string $throughKey;
   protected ?string $entityAlias;

   public function __construct(string $entity, string $field, string $throughKey = null, string $entityAlias = null)
   {
      $this->ensureIsAValidThroughKey($throughKey, $entityAlias);
      $this->entity = $entity;
      $this->field = $field;
      $this->throughKey = $throughKey;
      $this->entityAlias = $entityAlias;
   }

   public function entity() : string {
      return $this->entity;
   }

   public function field() : string {
      return $this->field;
   }

   public function throughKey() : ?string {
      return $this->throughKey;
   }

   public function entityAlias() : ?string {
      return $this->entityAlias;
   }

   public function value() : string {
      if($this->entityAlias)
         return sprintf('%s.%s', $this->entityAlias, $this->field);
      return sprintf('%s.%s', $this->entity, $this->field);
   }

   public function __toString() : string {
      return $this->value();
   }

   private function ensureIsAValidThroughKey($throughKey = null, $alias = null){
      if(($throughKey !== null && $alias === null) || ($throughKey === null && $alias !== null))
         throw new InvalidArgumentException('Se debe indicar el throughKey y el alias del campo del filtro para el campo '. $this->value());
   }


}
