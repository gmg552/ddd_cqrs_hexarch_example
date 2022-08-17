<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Query;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use Qalis\Shared\Domain\Primitives\Struct;

class QueryResult implements Countable, IteratorAggregate
{
   private array $items;

   public function __construct(Struct ...$items)
   {
       $this->items = $items;
   }

   protected function type(): string
   {
      return Struct::class;
   }

   public function getIterator(): ArrayIterator
   {
      return new ArrayIterator($this->items());
   }

   public function count(): int
   {
        return count($this->items());
   }

   protected function items(): array
   {
      return $this->items;
   }

   public function toArray(): array{
      $result = [];
      foreach ($this->items as $item) {
         array_push($result, $item->toArray());
      }
      return $result;
   }

   public function first() : Struct {
      return $this->items[0];
   }
}
