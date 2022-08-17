<?php

namespace Qalis\Shared\Domain\Criteria;

class Criteria
{
   
   private FilterExpression $filterExpression;
   private ?Orders $orders; 
   private ?int $offset;
   private ?int $limit;


   public function __construct(FilterExpression $filterExpression, Orders $orders = null, int $offset = null, int $limit = null){
      $this->filterExpression = $filterExpression;
      $this->orders = $orders;
      $this->offset = $offset;
      $this->limit = $limit;
   }


   public function filterExpression() : FilterExpression
   {
      return $this->filterExpression;
   }

   public function orders() : ?Orders
   {
      return $this->orders;
   }


   public function offset() : ?int
   {
      return $this->offset;
   }

   public function limit() : ?int
   {
      return $this->limit;
   }

   public function hasOrderBy() : bool {
      return $this->orders !== null;
   }

   public function involvedFields() : array {
      $involvedFields = [];
      if($this->hasOrderBy())
         array_push($involvedFields, $this->orders()->involvedFields());
      return array_unique(array_merge($involvedFields, $this->filterExpression->involvedFields()), SORT_STRING);
   }

   public function involvedEntities() : array {
      $involvedEntities = [];
      if($this->hasOrderBy())
         array_push($involvedEntities, $this->orders()->involvedEntities());
      return array_unique(array_merge($involvedEntities, $this->filterExpression->involvedEntities()), SORT_REGULAR);
   }


   public function __toString(): string {
      $order = $this->orders? $this->orders() : '';
      $limit = $this->limit? ' LIMIT ' . $this->limit : '';
      $offset = $this->offset? ' OFFSET ' . $this->offset: '';
      return sprintf('%s%s%s%s', $this->filterExpression, $order, $offset, $limit);
   }
}