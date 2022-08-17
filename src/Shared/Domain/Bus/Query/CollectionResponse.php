<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Bus\Query;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Qalis\Shared\Domain\Bus\Query\Response;

abstract class CollectionResponse implements Countable, IteratorAggregate, Response
{
   protected $items;

   abstract function __construct();

   abstract protected function type(): string;

   public function getIterator(): ArrayIterator
   {
      return new ArrayIterator($this->items());
   }

   public function count(): int
   {
        return count($this->items());
   }

   public function add($item){
        array_push($this->items, $item);
   }

   protected function items(): array
   {
      return $this->items;
   }

   public function toArray(): array{
      //if (is_array($this->items)) return $this->items;
       $result = [];
      foreach ($this->items as $item) {
         array_push($result, (!is_array($item)) ? $item->toArray() : $item);
      }
      return $result;
   }
}
