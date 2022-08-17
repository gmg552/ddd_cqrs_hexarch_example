<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Qalis\Shared\Domain\UnitOfWork;

class SchemaUtils
{

   public static function createOrUpdatedDecimalField(string $tableName, string $field, int $length, int $decimals, string $afterField, bool $nullable){
      Schema::table($tableName, function (Blueprint $table) use ($tableName, $field, $length, $decimals, $afterField, $nullable){
         if(!Schema::hasColumn($tableName, $field)) {
            if($nullable)
               $table->decimal($field, $length, $decimals)->nullable()->after($afterField);
            else
               $table->decimal($field, $length, $decimals)->after($afterField);
         }
         else{
            if($nullable)
               $table->decimal($field, $length, $decimals)->nullable()->change();
            else
               $table->decimal($field, $length, $decimals)->change();
         }
      });
   }

   public static function createOrUpdatedStringField(string $tableName, string $field, int $length, string $afterField, bool $nullable){
      Schema::table($tableName, function (Blueprint $table) use ($tableName, $field, $length, $afterField, $nullable){
         if(!Schema::hasColumn($tableName, $field)) {
            if($nullable)
               $table->string($field, $length)->nullable()->after($afterField);
            else
               $table->string($field, $length)->after($afterField);
         }
         else{
            if($nullable)
               $table->string($field, $length)->nullable()->change();
            else
               $table->string($field, $length)->change();
         }
      });
   }


   public static function createOrUpdatedIntegerField(string $tableName, string $field, string $afterField, bool $nullable){
      Schema::table($tableName, function (Blueprint $table) use ($tableName, $field, $afterField, $nullable){
         if(!Schema::hasColumn($tableName, $field)) {
            if($nullable)
               $table->integer($field)->nullable()->after($afterField);
            else
               $table->integer($field)->after($afterField);
         }
         else{
            if($nullable)
               $table->integer($field)->nullable()->change();
            else
               $table->integer($field)->change();
         }
      });
   }


   public static function createOrUpdatedDateField(string $tableName, string $field, string $afterField, bool $nullable){
      Schema::table($tableName, function (Blueprint $table) use ($tableName, $field, $afterField, $nullable){
         if(!Schema::hasColumn($tableName, $field)) {
            if($nullable)
               $table->date($field)->nullable()->after($afterField);
            else
               $table->date($field)->after($afterField);
         }
         else{
            if($nullable)
               $table->date($field)->nullable()->change();
            else
               $table->date($field)->change();
         }
      });
   }

   public static function createOrUpdatedBooleanField(string $tableName, string $field, string $afterField, bool $nullable){
      Schema::table($tableName, function (Blueprint $table) use ($tableName, $field, $afterField, $nullable){
         if(!Schema::hasColumn($tableName, $field)) {
            if($nullable)
               $table->boolean($field)->nullable()->after($afterField);
            else
               $table->boolean($field)->after($afterField);
         }
         else{
            if($nullable)
               $table->boolean($field)->nullable()->change();
            else
               $table->boolean($field)->change();
         }
      });
   }

   public static function deleteField(string $tableName, string $field){
      Schema::table($tableName, function (Blueprint $table) use($tableName, $field) {
         if(Schema::hasColumn($tableName, $field)) {
            $table->dropColumn($field);
         }
      });
   }
}
