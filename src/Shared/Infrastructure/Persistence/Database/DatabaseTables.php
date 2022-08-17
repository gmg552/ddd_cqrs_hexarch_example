<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence\Database;

use InvalidArgumentException;
use Qalis\Shared\Domain\Utils\StringUtils;

class DatabaseTables
{

   private static array $nonRegularTableNames = [
      'nonconformity' => 'non_conformities'
   ];

   public static function entityToDatabaseTable(string $entity) : string {
      $lowerEntity = strtolower($entity);
      if(array_key_exists($lowerEntity, self::$nonRegularTableNames))
         return StringUtils::toSnakeCase(self::$nonRegularTableNames[$lowerEntity]);
      return StringUtils::toSnakeCase($entity).'s';
   }

   public static function entityFieldToDatabaseField(string $fieldName) : string {
      if(self::isEntityKey($fieldName))
         return 'uuid';
      return StringUtils::toSnakeCase($fieldName);
   }

   private static function isEntityKey(string $columnName) : bool {
      return $columnName === 'id';
   }



   private static array $databaseTables = [
      'auditors' => [
         'is_a' => [
            [ 'table' => 'subjects', 'fk' => 'auditors.id', 'pk' => 'subjects.id' ]
         ],
         'relations' => [
            'strawAuditorAudits' => [
               [ 'table' => 'audits', 'alias' => 'straw_auditor_audits', 'fk' => 'straw_auditor_audits.straw_auditor_id', 'pk' => 'auditors.id' ]
            ],
            'realAuditor_audits' => [
               [ 'table' => 'audits', 'alias' => 'real_auditor_audits', 'fk' => 'real_auditor_audits.real_auditor_id', 'pk' => 'auditors.id' ]
            ]
         ]
      ],
      'audits' => [
         'relations' => [
            'realAuditor' => [
               [ 'table' => 'auditors', 'alias' => 'real_auditors', 'fk' => 'audits.real_auditor_id', 'pk' => 'real_auditors.id' ],
            ],
            'strawAuditor' => [
               [ 'table' => 'auditors', 'alias' => 'straw_auditors', 'fk' => 'audits.straw_auditor_id', 'pk' => 'straw_auditors.id'],
            ],
            'operator' => [
               [ 'table' => 'operators', 'fk' => 'audits.operator_id', 'pk' => 'operators.id' ]
            ],
            'nonConformities' => [
               [ 'table' => 'non_conformities', 'fk' => 'non_conformities.audit_id', 'pk' => 'audits.id' ]
            ]
         ]
      ],
      'schemes' => [
         'is_a' => [
            [ 'table' => 'services', 'fk' => 'schemes.id', 'pk' => 'services.id' ]
         ],
         'relations' => [
            'parent' => [
               'table' => 'schemes', 'alias' => 'parents', 'fk' => 'schemes.parent_id', 'pk' => 'parents.id'
            ]
         ]
      ]
   ];

   public static function joins(string $table, string $relationName) : string {
      self::ensureTableExists($table);
      self::ensureRelationExists($table, $relationName);
      return self::$databaseTables[$table]['relations'][$relationName];
   }

   public static function isAJoins(string $table) : string {
      self::ensureTableExists($table);
      return self::$databaseTables[$table]['is_a'];
   }

   private static function ensureRelationExists(string $table, string $relationName) : void {
      if(!isset(self::$databaseTables[$table]))
         throw new InvalidArgumentException("<$relationName> no se reconoce como relación de <$table>.");

   }

   private static function ensureTableExists(string $table) : void {
      if(!isset(self::$databaseTables[$table]))
         throw new InvalidArgumentException("<$table> no se reconoce como tabla de la base de datos.");
   }

}
