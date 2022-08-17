<?php

namespace Qalis\Shared\Infrastructure\Queryable;

use ArrayIterator;
use Countable;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use IteratorAggregate;
use Qalis\Shared\Domain\Queryable\Queryable;
use Qalis\Shared\Domain\Queryable\QueryableExpression;
use Qalis\Shared\Domain\Queryable\QueryableExpressionItem;
use Qalis\Shared\Domain\Queryable\QueryableFilter;
use Qalis\Shared\Domain\Queryable\QueryableFilterField;
use Qalis\Shared\Domain\Queryable\QueryableFilterOperator;
use Qalis\Shared\Domain\Queryable\QueryableFilterValue;
use Qalis\Shared\Domain\Queryable\QueryableHasFilter;
use Qalis\Shared\Domain\Queryable\QueryableLogicOperator;
use RuntimeException;

class LaravelQueryable implements Queryable, Countable, IteratorAggregate {

   private Collection $data;

   public function __construct(array $data)
   {
      $this->data = collect($data);
   }

   public function getIterator(): ArrayIterator
   {
      return new ArrayIterator($this->data->toArray());
   }

   public function toArray() : array {
      return $this->data->toArray();
   }

   private function evalHasFilter(QueryableHasFilter $hasFilter, array $row) : bool {
      $field = $hasFilter->field()->name();
      if(is_array($row[$field])){
         $queryable = new self($row[$field]);
         return $queryable->where($hasFilter->expression())->count() > 0;
      }
      throw new InvalidArgumentException("El campo <$field> en whereHas no hace referencia a un array en la colección de datos en $row");
   }

   public function where(QueryableExpression $expression) : Queryable {
      if($expression->count() == 0)
         return new self($this->data->toArray());

      $filteredData = $this->data->filter(function($row) use($expression){
         return $this->filterRow($row, $expression);
      });
      return new self($filteredData->toArray());
   }

   private function filterRow(array $row, QueryableExpression $expression) {
      $evalResult = false;
      $lastBinaryLogicOperator = null;
      $applyNotOperator = false;

      foreach ($expression as $expressionItem) {
         if ($expressionItem instanceof QueryableFilter) {
            $newValue = $this->negateIfApply($this->evalFilterInRow($expressionItem, $row), $applyNotOperator);
            $evalResult = $this->applyLogicOperatorIfRequired($newValue, $evalResult, $lastBinaryLogicOperator);
         }
         else if($expressionItem instanceof QueryableHasFilter){
            $newValue = $this->negateIfApply($this->evalHasFilter($expressionItem, $row), $applyNotOperator);
            $evalResult = $this->applyLogicOperatorIfRequired($newValue, $evalResult, $lastBinaryLogicOperator);
         }
         else if($expressionItem instanceof QueryableLogicOperator){
            if($expressionItem->equals(QueryableLogicOperator::NOT))
               $applyNotOperator = true;
            else{
               $applyNotOperator = false;
               $lastBinaryLogicOperator = $expressionItem;
            } 
         }
         else if($expressionItem instanceof QueryableExpression){
            $newValue = $this->negateIfApply($this->filterRow($row, $expressionItem), $applyNotOperator);
            $evalResult = $this->applyLogicOperatorIfRequired($newValue, $evalResult, $lastBinaryLogicOperator);
         }
         else {
            throw new InvalidArgumentException(sprintf("No se reconoce el elemento <%s> en la QueryableExpression al evaluar $row", get_class($expressionItem)));
         }
      }
      return $evalResult;
   }

   private function applyLogicOperatorIfRequired($value1, $value2 = null, QueryableLogicOperator $logicOperator = null){
      if($logicOperator)
         return $this->applyBinaryLogicOperator($value1, $value2, $logicOperator->value());
      return $value1;
   }

   private function negateIfApply(bool $value, $notOperator){
      if($notOperator)
         return !$value;
      return $value;
   }

 
   private function evalFilterInRow(QueryableFilter $filter, array $row) : bool {
      $value1 = $row[$filter->operand1()->name()];
      if($filter->operand2() instanceof QueryableFilterValue)
         $value2 = $filter->operand2()->value();
      else if($filter->operand2() instanceof QueryableFilterField)
         $value2 = $row[$filter->operand2()->name()];
      return $this->applyFilterOperator($value1, $value2, $filter->operator()->value());
   }



   private function applyFilterOperator($value1, $value2, string $operator) : bool {
      switch ($operator) {
         case QueryableFilterOperator::EQUAL:
            return $value1 == $value2;
         case QueryableFilterOperator::NOT_EQUAL:
            return $value1 != $value2;
         case QueryableFilterOperator::CONTAINS:
            return str_contains($value1, $value2);
         case QueryableFilterOperator::NOT_CONTAINS:
            return !str_contains($value1, $value2);
         case QueryableFilterOperator::GET:
            return $value1 >= $value2;
         case QueryableFilterOperator::GT:
            return $value1 > $value2;
         case QueryableFilterOperator::LET:
            return $value1 <= $value2;
         case QueryableFilterOperator::LT:
            return $value1 < $value2;
         default:
            throw new InvalidArgumentException("No se reconoce el QueryableOperator <$operator>");
      }
   }

   private function applyBinaryLogicOperator(bool $value1, bool $value2, string $operator){
      if($operator === QueryableLogicOperator::AND)
         return $value1 && $value2;
      else if($operator === QueryableLogicOperator::OR)
         return $value1 || $value2;
      else {
         throw new InvalidArgumentException("No se reconoce QueryableLogicOperator <$operator>");
      }
   }




   public function count() : int {
      return count($this->data);
   }
   
}
