<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Qalis\Shared\Domain\Criteria\Criteria;
use Qalis\Shared\Domain\Criteria\Filter;
use Qalis\Shared\Domain\Criteria\FilterExpression;
use Qalis\Shared\Domain\Criteria\FilterOperator;
use Qalis\Shared\Domain\Criteria\FilterValue;
use Qalis\Shared\Domain\Criteria\LogicOperator;
use Qalis\Shared\Domain\Criteria\Order;
use Qalis\Shared\Domain\Criteria\Orders;

class CriteriaToEloquentMapper
{


   private static function applyOrderBy($query, Orders $orders): void
   {
      foreach ($orders as $order) {
         $query->orderBy(
            sprintf('%s.%s',$order->field()->entity(), $order->field()->field()), 
            self::mapOrderType($order->type())
         );
      }
   }

   private static function mapOrderType(string $orderType) : string {
      if($orderType === Order::ASC) 
         return 'ASC';
      else if($orderType === Order::DESC)
         return 'DESC';
      throw new InvalidArgumentException(sprintf('No se reconoce el tipo de orden <%s> en el criterio de filtrado', $orderType));
   }

   public static function convert($query, Criteria $criteria)
   {
      self::applyOrderBy($query, $criteria->orders());
      self::applyFilterExpression($query, $criteria->filterExpression());
      self::applyLimit($query, $criteria->limit());
      self::applyOffset($query, $criteria->offset());
   }

   private static function applyFilterExpression($query, FilterExpression $filterExpression){
      $query->whereRaw(self::getFilterExpression($filterExpression));
   }

   private static function applyLimit($query, int $limit) {
      $query->limit($limit);
   }

   private static function applyOffset($query, int $offset){
      $query->offset($offset);
   }

   private static function getFilterExpression(FilterExpression $filterExpression) : string {
      
      $rawSql = '';
      foreach ($filterExpression as $filterItem) {
         if ($filterItem instanceof Filter) {
            $rawSql .= self::mapFilter($filterItem).' ';
         }
         else if($filterItem instanceof LogicOperator){
            $rawSql .= self::mapLogicOperator($filterItem).' ';
         }
         else if($filterItem instanceof FilterExpression){
            $rawSql .= sprintf('(%s)', self::getFilterExpression($filterItem));
         }
      }
      return $rawSql;
   }

   private static function mapLogicOperator(LogicOperator $logicOperator) {
      switch ($logicOperator->value()) {
         case LogicOperator::AND:
            return 'AND';
         case LogicOperator::OR:
            return 'OR';
         default:
            throw new InvalidArgumentException(sprintf('No se reconoce el operador lógico <%s> en la expresión de filtrado', $logicOperator->value()));
      }
   }

   private static function mapFilter(Filter $filter): string
   {
      return sprintf('%s.%s %s %s',  
         $filter->operand1()->entity(), 
         $filter->operand1()->field(), 
         self::mapFilterOperator($filter->operator()),
         $filter->operand2()->value()
      );
   }

   private static function mapFilterOperator(FilterOperator $filterOperator): string
   {
      switch ($filterOperator->value()) {
         case FilterOperator::CONTAINS:
            return 'like';
         case FilterOperator::NOT_CONTAINS:
            return 'not like';
         case FilterOperator::EQUAL:
            return '=';
         case FilterOperator::NOT_EQUAL:
            return '!=';
         case FilterOperator::GET:
            return '>=';
         case FilterOperator::GT:
            return '>';
         case FilterOperator::LET:
            return '<=';
         case FilterOperator::LT:
            return '<';
         default:
            throw new InvalidArgumentException(sprintf('No se reconoce el operador para el filtrado <%s>', $filterOperator->value()));
      }
   }
}
