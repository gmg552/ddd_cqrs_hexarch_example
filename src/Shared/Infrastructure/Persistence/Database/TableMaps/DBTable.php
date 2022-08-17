<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence\Database\TableMaps;

interface DBTable
{

   

   public function directRelationships() : array;
   public function joinsTo(string $targetTable, $throughKey = null) : array;
   public function tableName() : string;
   public function localKeyOf(string $foreignKey) : string;

}