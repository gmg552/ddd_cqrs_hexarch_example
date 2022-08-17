<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFieldValues;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Qalis\Certification\Shared\Domain\EntityFields\EntityFieldName;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldValidation;

class SchemeEntityFieldValues implements Countable, IteratorAggregate
{
   private array $values;

   function __construct(SchemeEntityFieldValue ...$values){
      $this->values = $values;
   }

   protected function type(): string{
      return SchemeEntityFieldValue::class;
   }

   public function getIterator(): ArrayIterator
   {
      return new ArrayIterator($this->values);
   }

   public function push(SchemeEntityFieldValue $value) : void {
      array_push($this->values, $value);
   }

   public function count(): int
   {
      return count($this->values);
   }

   public function toArray(): array {
       $array = [];
       foreach($this->values as $value) {
           $array[$value->fieldName()->value()] = $value->value();
       }
       return $array;
   }

}
