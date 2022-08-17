<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Criteria;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

use function Lambdish\Phunctional\map;

class FilterExpression extends FilterExpressionItem implements Countable, IteratorAggregate
{
   private array $filterItems;

   public function __construct(FilterExpressionItem ...$filterItems)
   {
      $this->ensureIsAValidExpression(...$filterItems);
      $this->filterItems = $filterItems;
   }

   public function getIterator(): ArrayIterator
   {
      return new ArrayIterator($this->filterItems);
   }

   public function count(): int
   {
      return count($this->filterItems);
   }

   public function add(FilterExpressionItem $filterItem)
   {
      array_push($this->filterItems, $filterItem);
   }

   public function filters() : array {
      $result = [];
      foreach ($this->filterItems as $filterItem) {
         if($filterItem instanceof Filter)
            array_push($result, $filterItem);
      }
      return $result;
   }

   public function involvedFields() : array {
      $result = [];
      foreach ($this->filterItems as $filterItem) {
         if($filterItem instanceof Filter)
            array_push($result, $filterItem->involvedFields());
      }
      return array_unique($result);
   }

   public function involvedEntities() : array {
      $result = [];
      foreach ($this->filterItems as $filterItem) {
         if($filterItem instanceof Filter)
            array_push($result, $filterItem->involvedEntities());
      }
      return array_unique($result, SORT_REGULAR);
   }

   protected function items(): array
   {
      return $this->filterItems;
   }

   public function __toString(): string
   {
      return $this->toString(...$this->filterItems);
   }

   private function toString(FilterExpressionItem ...$filterItems): string
   {
      return '(' . join(' ', map(static fn (FilterExpressionItem $item) => '' . $item, $filterItems)) . ')';
   }

   private function ensureIsAValidExpression(FilterExpressionItem ...$filterItems)
   {
      try {
         $expression = $this->toEvaluablePHPExpression(...$filterItems);
         eval('return '.$expression.';');
      }
      catch (\Throwable $exc) {
         throw new InvalidArgumentException(sprintf('La expresión de filtrado <%s> no es válida', $this->toString(...$filterItems)));
      }
   }

   private function toEvaluablePHPExpression(FilterExpressionItem ...$filterItems) : string {
      $evalExpression = '';
      foreach ($filterItems as $item) {
         if (get_class($item) == LogicOperator::class){
            if($item->equals(LogicOperator::AND))
               $evalExpression .= ' && ';
            else if($item->equals(LogicOperator::OR))
               $evalExpression .= ' || ';
         }
         else if (get_class($item) == Filter::class)
            $evalExpression .= ' true ';
         else if (get_class($item) == FilterExpression::class) {
            $evalExpression .= $this->toEvaluablePHPExpression(...$item->items());
         }
      }
      return $evalExpression;
   }
}
