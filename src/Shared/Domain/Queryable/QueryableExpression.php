<?php

namespace Qalis\Shared\Domain\Queryable;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

use function Lambdish\Phunctional\map;

class QueryableExpression extends QueryableExpressionItem implements Countable, IteratorAggregate {

   private array $expressionItems;
   
   public function __construct(QueryableExpressionItem ...$expressionItems)
   {
      $this->ensureIsAValidExpression(...$expressionItems);
      $this->expressionItems = $expressionItems;
   }

   static public function fromArray(array $filterCondition) : QueryableExpression {

       $itemsQueryableExpression = new QueryableExpression();
       foreach($filterCondition as $expressionItem) {

           switch (key($expressionItem)) {
               case "filter":

                   $itemsQueryableExpression->add(
                       new QueryableFilter(
                           new QueryableFilterField($expressionItem[key($expressionItem)]['field1']),
                           new QueryableFilterOperator($expressionItem[key($expressionItem)]['operator']),
                           self::getFieldOrValue($expressionItem[key($expressionItem)])
                       ));
                   break;

               case "logic_operator":

                   $itemsQueryableExpression->add(new QueryableLogicOperator($expressionItem[key($expressionItem)]));
                   break;

               case "has":

                   $itemsQueryableExpression->add(
                       new QueryableHasFilter(
                           new QueryableFilterField($expressionItem[key($expressionItem)]['field1']),
                           self::fromArray($expressionItem[key($expressionItem)]['expression'])
                       ));
                   break;

               case "expression":

                   $itemsQueryableExpression->add(
                       self::fromArray($expressionItem[key($expressionItem)]));
                   break;

           }
       }

       return $itemsQueryableExpression;

   }

    static private function getFieldOrValue($value) {
        return isset($value['value']) ? new QueryableFilterValue($value['value']) : new QueryableFilterField($value['field2']);
    }
 
    public function getIterator(): ArrayIterator
    {
      return new ArrayIterator($this->expressionItems);
    }

    public function count(): int
    {
      return count($this->expressionItems);
    }

    public function add(QueryableExpressionItem $expressionItems)
    {
      array_push($this->expressionItems, $expressionItems);
    }

    public function filters() : array {
      $result = [];
      foreach ($this->expressionItems as $expressionItem) {
         if($expressionItem instanceof QueryableFilter)
            array_push($result, $expressionItem);
      }
      return $result;
    }


    protected function items(): array
    {
      return $this->expressionItems;
    }

    public function __toString(): string
    {
      return $this->toString(...$this->expressionItems);
    }

    private function toString(QueryableExpressionItem ...$expressionItems): string
    {
      return '(' . join(' ', map(static fn (QueryableExpressionItem $item) => '' . $item, $expressionItems)) . ')';
    }

    private function ensureIsAValidExpression(QueryableExpressionItem ...$expressionItems)
    {
      try {
         $expression = $this->toEvaluablePHPExpression(...$expressionItems);
         eval('return '.$expression.';');
      }
      catch (\Throwable $exc) {
         throw new InvalidArgumentException(sprintf('La expresión de filtrado <%s> no es válida', $this->toString(...$expressionItems)));
      }
    }

    private function toEvaluablePHPExpression(QueryableExpressionItem ...$expressionItems) : string {
      $evalExpression = '';
      foreach ($expressionItems as $item) {
         if (get_class($item) == QueryableLogicOperator::class){
            if($item->equals(QueryableLogicOperator::AND))
               $evalExpression .= ' && ';
            else if($item->equals(QueryableLogicOperator::OR))
               $evalExpression .= ' || ';
            else if($item->equals(QueryableLogicOperator::NOT))
            $evalExpression .= ' !';
         }
         else if (get_class($item) == QueryableFilter::class)
            $evalExpression .= ' true ';
          else if (get_class($item) == QueryableHasFilter::class)
            $evalExpression .= $this->toEvaluablePHPExpression(...$item->expression()->items());
         else if (get_class($item) == QueryableExpression::class) {
            $evalExpression .= $this->toEvaluablePHPExpression(...$item->items());
         }
      }
      return $evalExpression;
    }

}
