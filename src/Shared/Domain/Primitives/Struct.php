<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Primitives;

use InvalidArgumentException;

class Struct
{
   private array $values;
   private array $types;

   /**
    *
    */
   public function __construct(array $types, array $values = null)
   {
      $this->values = $values? $values : [];
      $this->types = $types;
   }

   public function toArray(): array
   {
      return $this->values;
   }

   public function __get(string $propertyName){
      $this->ensurePropertyExists($propertyName);
      return $this->values[$propertyName];
   }

   public function __set(string $propertyName, string $value){
      $this->ensurePropertyExists($propertyName);
      $this->ensureTypeIsRight($propertyName);
      $this->values[$propertyName] = $value;
   }

   private function ensurePropertyExists(string $propertyName){
      if(!array_key_exists($propertyName, $this->values))
         throw new InvalidArgumentException("No se encuentra la propiedad <$propertyName> en esta estructura.");
   }

   private function ensureTypeIsRight(string $propertyName){
      if($this->types[$propertyName] === null)
         return;

      $type = gettype($this->values[$propertyName]);

      if($type === 'object'){
         $class = get_class($this->values[$propertyName]);
         if($class !== $this->types[$propertyName])
            throw new InvalidArgumentException("El tipo de <$propertyName> debe ser <$this->types[$propertyName]> en la estructura, pero se encontró <$class>");
      }
      else if($type !== $this->types[$propertyName])
         throw new InvalidArgumentException("El tipo de <$propertyName> debe ser <$this->types[$propertyName]> en la estructura, pero se encontró <$type>");
   }

}
