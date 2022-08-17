<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence\Database\TableMaps;

use InvalidArgumentException;

class EntityToTable
{

   private static $map =
   [
      'audit' => [
         'table' => 'audits'
      ],
      'auditor' => [
         'table' => 'auditors',
      ],
      'operator' => [
         'table' => 'operators'
      ],
      'scheme' => [
         'table' => 'schemes',
      ],
      'nonConformity' => [
         'table' => 'non_conformities',
      ],
      'auditedScheme' => [
         'table' => 'audited_schemes'
      ]
      //A completar
   ];

   public static function tableOf(string $entityName) : string {
      self::ensureAreValidParams($entityName);
      return self::$map[$entityName]['table'];
   }

   private static function ensureAreValidParams(string $entityName) : void {
      if(!isset(self::$map[$entityName]['table']))
         throw new InvalidArgumentException("No se encuentra la entidad <$entityName> en el mapa de entidades a tablas");
   }
   

}