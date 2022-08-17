<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Entities;

use InvalidArgumentException;

class Relations
{

   private static $relations = 	[
      'audit' => [
         'with' => [
            'realAuditor' => 'auditor',
            'strawAuditor' => 'auditor',
            'operator' => 'operator',
            'nonConformities' => 'nonConformity',
            'auditedSchemes' => 'auditedScheme'
         ]
      ],
      'auditor' => [
         'isA' => [ 'subject' ],
         'with' => [
            'strawAuditorAudits' => 'audit',
            'realAuditorAudits' => 'audit'
         ]
      ],
      'operator' => [
         'with' => [
            'audits' => 'audit',
            'schemeOrders' => 'schemeOrder'
         ]
      ],
      'scheme' => [
         'with' => [
            'parent' => 'scheme'
         ]
      ],
      'nonConformity' => [
         'with' => [
            'audit' => 'audit'
         ]
      ]
   ];

   public static function with(string $entityName, string $relationshipName) : string {
      self::ensureAreValidParams($entityName, $relationshipName);
      return self::$relations[$entityName]['with'][$relationshipName];
   }

   private static function ensureAreValidParams(string $entityName, string $relationshipName) : void {
      if(!isset(self::$relations[$entityName]))
         throw new InvalidArgumentException("No se encuentra la entidad <$entityName> en el listado de relaciones.");

      if(!isset(self::$relations[$entityName]['with'][$relationshipName]))
         throw new InvalidArgumentException("No se encuentra la relación <$relationshipName> en el listado de relaciones.");  
   }

}