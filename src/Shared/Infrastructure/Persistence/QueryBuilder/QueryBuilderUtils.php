<?php

namespace Qalis\Shared\Infrastructure\Persistence\QueryBuilder;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class QueryBuilderUtils
{

    public const OR_OPERATOR_TYPE = 'or';
    public const AND_OPERATOR_TYPE = 'and';

   public static function applyJoins(Builder $query, array $relationships) : void {
      foreach ($relationships as $relationship) {
         $query->join(...$relationship);
      }
   }

   public static function notDeleted(Builder $query) {
       $query->whereNull(self::getTable($query->from).'.deleted_at');
       if ($query->joins) foreach($query->joins as $join) {
            $join->whereNull(self::getTable($join->table).'.deleted_at');
       }
       return $query;
   }

   private static function getTable($tableName) {
       $tableName = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $tableName);
       $tableAlias = explode(" as ", $tableName);
       return $tableAlias[1] ?? $tableAlias[0];
   }

   public static function updateOrCreate(string $table, array $conditions, array $values, string $operatorCondition = 'and', bool $setTimeStamps = null, bool $isRawQuery = false){

      $timeStamps = ($setTimeStamps || $setTimeStamps === null) ? ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()] : [];

      $values = $values + $timeStamps;

       $first = DB::table($table)->when($conditions, function($query, $conditions) use ($operatorCondition, $isRawQuery) {

           if ($operatorCondition == self::OR_OPERATOR_TYPE) {
               if ($isRawQuery) {
                   foreach($conditions as $key => $value) {
                       $query->orWhereRaw($key . ' = ' . $value);
                   }
               } else {
                   foreach ($conditions as $key => $value) {
                       $query->orWhere($key, $value);
                   }
               }
           } else {
               if ($isRawQuery) {
                   foreach($conditions as $key => $value) {
                       $query->whereRaw($key . ' = ' . $value);
                   }
               } else {
                   foreach($conditions as $key => $value) {
                       $query->where($key, $value);
                   }
               }
           }
       })->first();

       if ($isRawQuery) $conditions = self::cleanRawConditions($conditions);

       if ($first) {
           $firstId = $first->id;
           DB::table($table)->where('id', $firstId)->update($conditions + $values);
       } else {
           $firstId = DB::table($table)->insertGetId(
               $conditions + $values
           );
       }

      return $firstId;
   }

   private static function cleanRawConditions($conditions): array {
       foreach($conditions as $key => &$condition) {
           $condition = self::clearBinaryRaw($condition);
       }
       return $conditions;
   }

   private static function clearBinaryRaw($condition) {
       $condition = str_replace('BINARY ', '', $condition);
       $condition = str_replace('"', '', $condition);
       return str_replace("'", '', $condition);
   }


}
