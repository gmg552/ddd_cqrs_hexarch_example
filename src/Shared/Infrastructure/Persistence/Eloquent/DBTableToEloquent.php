<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent;


class DBTableToEloquent
{

   public static function applyJoins(array $relationships, $query) : void {
      foreach ($relationships as $relationship) {
         $query->join(...$relationship);
      }
   }

}