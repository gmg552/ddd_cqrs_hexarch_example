<?php

namespace Qalis\Shared\Infrastructure\Persistence\QueryBuilder;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Qalis\Shared\Domain\Primitives\Struct;
use Qalis\Shared\Domain\Query\Query;
use Qalis\Shared\Domain\Query\QueryResult;
use Qalis\Shared\Domain\Query\QueryServiceProvider;
use Qalis\Shared\Domain\Utils\StringUtils;
use Qalis\Shared\Infrastructure\Persistence\Database\DatabaseTables;
use stdClass;

class LaravelQueryServiceProvider implements QueryServiceProvider
{

   public function __invoke(Query $query) : QueryResult {
      $queryBuilder = $this->resolveFromClause($query);
      $this->prepareSelect($query, $queryBuilder);
      if($query->hasCriteria())
         (new CriteriaToQueryBuilderMapper($query->criteria(), $queryBuilder))->apply();
      return $this->queryBuilderCollectionToQueryResult($queryBuilder->get());
   }


   private function resolveFromClause(Query $query) : Builder {
      $baseTable = DatabaseTables::entityToDatabaseTable($query->baseEntity());
      $queryBuilder = DB::table($baseTable);
      for($i = 1; $i < $query->relationshipsCounter(); $i++){
         $tableName = DatabaseTables::entityToDatabaseTable($query->entityNameAt($i));
         $tableLocalField = DatabaseTables::entityToDatabaseTable($query->localEntityNameAt($i)).'.'.StringUtils::toSnakeCase($query->localFieldNameAt($i));
         $tableForeignField =  DatabaseTables::entityToDatabaseTable($query->foreignEntityNameAt($i)).'.'.StringUtils::toSnakeCase($query->foreignFieldNameAt($i));
         if($query->relationshipAt($i) == Query::JOIN){
            $queryBuilder->join($tableName, $tableLocalField, $query->operatorAt($i), $tableForeignField);
         }
         else if($query->relationshipAt($i) == Query::LEFT_JOIN){
            $queryBuilder->leftJoin($tableName, $tableLocalField, $query->operatorAt($i), $tableForeignField);
         }
         else{
            $relationship = $query->relationshipAt($i);
            throw new InvalidArgumentException("No se reconoce la relación $relationship en Query");
         }
      }
      return $queryBuilder;
   }

   private function prepareSelect(Query $query, Builder $queryBuilder) : Builder {
      $newSelectFields = [];
      for ($i = 0; $i < $query->selectCounter(); $i++) {
         $newSelectField = $this->handleSelectExpression($query, $i);
         array_push($newSelectFields, $newSelectField);
      }
      return $queryBuilder->select($newSelectFields);
   }

   private function handleSelectExpression(Query $query, int $index) : string {
      $expression = $query->selectExpressionAt($index);
      if($query->selectIsEntityAndAsteriskAt($index)){
         $tableName = DatabaseTables::entityToDatabaseTable($query->selectEntityNameAt($index));
         return $tableName.'.*';
      }else if($query->selectIsAsteriskAt($index)){
         return '*';
      }
      else if($query->selectIsEntityAndFieldAt($index)){
         return $this->handleEntityAndFieldSelectExpression(
            $query->selectEntityNameAt($index),
            $query->selectFieldNameAt($index),
            $query->selectFieldAliasAt($index)
         );
      }
      else {
         throw new InvalidArgumentException("No se reconoce la expressión en SELECT <$expression>");
      }
   }

   private function handleEntityAndFieldSelectExpression(string $entityName, string $fieldName, string $alias = null){
      if($alias)
         $aliasString = ' as '.$alias;
      else if($fieldName == 'uuid')
         $aliasString = ' as id';
      else
         $aliasString = ' as '.$fieldName;

      $tableName = DatabaseTables::entityToDatabaseTable($entityName);
      $columName = DatabaseTables::entityFieldToDatabaseField($fieldName);

      if($fieldName == 'uuid')
         $newSelectField = DB::raw("lower(hex($tableName.uuid)) $aliasString");
      else
         $newSelectField = $tableName.'.'.$columName.$aliasString;

      return $newSelectField;

   }

   private function queryBuilderCollectionToQueryResult(Collection $collection) : QueryResult{
      $structs = [];
      foreach ($collection as $record) {
         array_push($structs, $this->stdClassRecordToStruct($record));
      }
      return new QueryResult(...$structs);
   }

   private function stdClassRecordToStruct(stdClass $record) : Struct {
      $recordArray = (array) $record;
      $structArray = [];
      foreach ($recordArray as $key => $value) {
         $structArray[StringUtils::toCamelCase($key)] = $value;
      }
      return new Struct([], $structArray);
   }

}
