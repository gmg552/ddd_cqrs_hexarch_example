<?php

namespace Qalis\Shared\Domain\Criteria;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class Orders implements Countable, IteratorAggregate
{

   private array $orderItems;
   public function __construct(Order ...$orders){
      $this->orderItems = $orders;
   }

   public function count() : int {
      return count($this->orderItems);
   }
   
   public function getIterator(): ArrayIterator
   {
      return new ArrayIterator($this->orderItems);
   }

   public function __toString() : string {
      return 'ORDER BY '.join(', ', $this->orderItems);
   }

   public function involvedFields() : array {
      $involvedFields = [];
      foreach ($this->orderItems as $orderBy) {
         if(!in_array($orderBy->field()->value(), $involvedFields, true)){
            array_push($involvedFields, $orderBy->field());
         }
      }
      return $involvedFields;
   }

   public function involvedEntities() : array {
      $involvedEntities = [];
      foreach ($this->orderItems as $orderBy) {
         $entity = [
            'entity' => $orderBy->field()->entity(),
            'alias' => $orderBy->field()->entityAlias()
         ];
         array_push($involvedEntities, $entity);
      }
      return array_unique($involvedEntities, SORT_REGULAR);
   }

}