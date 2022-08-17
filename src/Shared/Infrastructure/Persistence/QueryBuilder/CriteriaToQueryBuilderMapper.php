<?php

namespace Qalis\Shared\Infrastructure\Persistence\QueryBuilder;

use Illuminate\Database\Query\Builder;
use InvalidArgumentException;
use Qalis\Shared\Domain\Criteria\Criteria;
use Qalis\Shared\Domain\Criteria\Filter;
use Qalis\Shared\Domain\Criteria\FilterExpression;
use Qalis\Shared\Domain\Criteria\FilterField;
use Qalis\Shared\Domain\Criteria\FilterOperator;
use Qalis\Shared\Domain\Criteria\LogicOperator;
use Qalis\Shared\Domain\Criteria\Order;
use Qalis\Shared\Domain\Utils\StringUtils;

class CriteriaToQueryBuilderMapper
{

   private Builder $query;
   private Criteria $criteria;
   private ?array $entityTables;


   public function __construct(Criteria $criteria, Builder $query, array $entityTables = null)
   {
      $this->query = $query;
      $this->criteria = $criteria;
      $this->entityTables = $entityTables;
   }


   public function query() : Builder {
      return $this->query;
   }

   public function criteria() : Criteria {
      return $this->criteria;
   }

   private  function applyOrderBy(): void
   {
      if($this->criteria->orders())
         foreach ($this->criteria->orders() as $order) {
            $this->query->orderBy(
               sprintf('%s.%s',$order->field()->entity(), $order->field()->field()),
               $this->mapOrderType($order->type())
            );
         }
   }

   private function mapOrderType(string $orderType) : string {
      if($orderType === Order::ASC)
         return 'ASC';
      else if($orderType === Order::DESC)
         return 'DESC';
      throw new InvalidArgumentException(sprintf('No se reconoce el tipo de orden <%s> en el criterio de filtrado', $orderType));
   }

   public function apply()
   {
      //$this->applyJoins();
      $this->applyFilterExpression();
      $this->applyOrderBy();
      $this->applyLimit();
      $this->applyOffset();

   }

   /*
   private function applyJoins(){
      $queryBulderJoins = [];
      $involvedEntities = $this->criteria()->involvedEntities();
      foreach ($involvedEntities as $entity) {
         $tableName = $this->mapEntityName($entity['entity']);
         $tableAliases = $entity['alias'] == null? null: $this->mapEntityName($entity['alias']);

         $joins = $this->dbTable->joinsTo($tableName, );
         foreach ($joins as $join) {
            array_push($queryBulderJoins, [ $join['referencedTable'], $join['foreignKey'], $join['localKey'] ]);
         }
      }
      $queryBulderJoins = array_unique($queryBulderJoins, SORT_REGULAR);
      foreach ($queryBulderJoins as $queryBulderJoin)
         $this->query()->join(...$queryBulderJoin);
   }*/

   private  function applyFilterExpression(){
      $applyOrWhere = false;
      foreach ($this->criteria->filterExpression() as $filterItem) {
         if($filterItem instanceof Filter){
            $this->applyFilter($filterItem, $applyOrWhere);
            $applyOrWhere = false;
         }
         else if($filterItem instanceof FilterExpression){
            if($applyOrWhere)
               $this->query->orWhere(function($localQuery) use($filterItem){
                  $this->applyFilterExpression($localQuery, $filterItem);
               });
            else
               $this->query->where(function($localQuery) use($filterItem){
                  $this->applyFilterExpression($localQuery, $filterItem);
               });
            $applyOrWhere = false;
         }
         else if($filterItem instanceof LogicOperator){
            $applyOrWhere = $filterItem->equals(LogicOperator::OR);
         }

      }
   }

   private function mapFieldName(string $fieldName) : string {
      if($this->isEntityKey($fieldName))
         return 'uuid';
      return StringUtils::toSnakeCase($fieldName);
   }

   private function mapEntityName(string $entityName) : string {
      if($this->entityTables != null && isset($this->entityTables['entityName']))
         return $this->entityTables['entityName'];

      if(!str_ends_with($entityName, 's'))
         return StringUtils::toSnakeCase($entityName) . 's';
      return $entityName;
   }

   private function mapFilterField(FilterField $filterField) : string {
      $columnName = $this->mapEntityName($filterField->entity()) . '.' . $this->mapFieldName($filterField->field());
      //if($this->isForeignKey($filterField->field()))
      //   return $this->mapForeignKey($columnName);
      return $columnName;
   }

   /*
   private function mapForeignKey(string $columnName) : string {
      return $this->dbTable->localKeyOf($columnName);
   }*/

   private function isEntityKey(string $columnName) : bool {
      return $columnName === 'id';
   }

   /*
   private function isForeignKey(string $columnName) : bool {
      return preg_match('[\s\S]Id$|[\s\S]_id$', $columnName);
   }*/

   private  function applyFilter(Filter $filter, bool $orWhere = null){

      $operand1 = $this->mapFilterField($filter->operand1());

      if($this->isEntityKey($filter->operand1()->field()))
         $operand2 = hex2bin($filter->operand2()->value());
      else if($filter->operand2() instanceof FilterField)
         $operand2 = $this->mapFilterField($filter->operand2());
      else
         $operand2 = $filter->operand2()->value();

      $operator =  $this->mapFilterOperator($filter->operator());

      if($filter->operand2() instanceof FilterField)
         $this->query->whereRaw(sprintf('%s %s %s', $operand1, $operator, $operand2));
      else if($filter->operand2() === null){
         $this->query->whereNull($operand1);
      }
      else if($orWhere)
         $this->query->orWhere($operand1, $operator, $operand2);
      else
         $this->query->where($operand1, $operator, $operand2);
   }

   private  function applyLimit() {
      if($this->criteria->limit())
         $this->query->limit($this->criteria->limit());
   }

   private  function applyOffset(){
      if($this->criteria->offset())
         $this->query->offset($this->criteria->offset());
   }


   private  function mapFilterOperator(FilterOperator $filterOperator): string
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
