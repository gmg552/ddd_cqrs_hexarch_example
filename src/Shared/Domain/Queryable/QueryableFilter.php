<?php

namespace Qalis\Shared\Domain\Queryable;


class QueryableFilter extends QueryableExpressionItem
{

   private QueryableFilterField $operand1;
   private QueryableFilterOperator $operator;
   private QueryableFilterOperand $operand2;

   public function __construct(QueryableFilterField $operand1, QueryableFilterOperator $operator, QueryableFilterOperand $operand2)
   {
      $this->operand1 = $operand1;
      $this->operator = $operator;
      $this->operand2 = $operand2;
   }


   public function operand1(): QueryableFilterField
   {
      return $this->operand1;
   }

   public function operator(): QueryableFilterOperator
   {
      return $this->operator;
   }

   public function operand2(): QueryableFilterOperand
   {
      return $this->operand2;
   }


   public function __toString(): string
   {
      return $this->operand1 . $this->operator . $this->operand2;
   }
}
