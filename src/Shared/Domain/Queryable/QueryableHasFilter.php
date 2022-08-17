<?php

namespace Qalis\Shared\Domain\Queryable;


class QueryableHasFilter extends QueryableExpressionItem
{

   private QueryableFilterField $field;
   private QueryableExpression $queryableExpression;
   public function __construct(QueryableFilterField $field, QueryableExpression $queryableExpression){
      $this->field = $field;
      $this->queryableExpression = $queryableExpression;
   }

   public function field() : QueryableFilterField {
      return $this->field;
   }

   public function expression() : QueryableExpression{
      return $this->queryableExpression;
   }

   public function __toString() : string {
      return $this->field . ' has item with ' .  $this->queryableExpression;
   }

}