<?php

namespace Qalis\Shared\Domain\Criteria;

class Filter extends FilterExpressionItem
{

   private FilterField $operand1;
   private FilterOperator $operator;
   private ?FilterOperand $operand2;

   public function __construct(FilterField $operand1, FilterOperator $operator, ?FilterOperand $operand2)
   {
      $this->operand1 = $operand1;
      $this->operator = $operator;
      $this->operand2 = $operand2;
   }


   public function operand1() : FilterField
   {
      return $this->operand1;
   }

   public function operator() : FilterOperator
   {
      return $this->operator;
   }

   public function operand2() : ?FilterOperand
   {
      return $this->operand2;
   }

   public function involvedFields() : array {
      $fields = [$this->operand1()->field()];
      if($this->operand2() instanceof CriteriaField)
         array_push($fields, $this->operand2());
      return $fields;
   }

   public function involvedEntities() : array {
      $entities = [
         [
            'entity' => $this->operand1()->entity(),
            'alias' => $this->operand1()->entityAlias()
         ]
      ];
      if($this->operand2() instanceof FilterField)
         array_push($entities, ['entity' => $this->operand2()->entity(), 'alias' => $this->operand2()->entityAlias()]);
      return $entities;
   }

   public function __toString(): string {
      if($this->operand2)
         return $this->operand1.$this->operator.$this->operand2;
      return $this->operand1.$this->operator.'null';
   }
}