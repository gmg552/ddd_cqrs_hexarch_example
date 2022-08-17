<?php

namespace Qalis\Shared\Domain\Query;

use InvalidArgumentException;
use Qalis\Shared\Domain\Criteria\Criteria;

/**
 * Representa una query sobre entidades del dominio
 */
class Query{


   private array $relationships;
   private ?Criteria $criteria;
   private array $select;

   public const JOIN = 'join';
   public const LEFT_JOIN = 'leftjoin';
   public const EQUAL = '=';
   public const NOT_EQUAL = '!=';


   private function __construct(string $entityName)
   {
      $this->relationships = [];
      $this->select = [];
      $this->criteria = null;
      array_push($this->relationships, ['relationship' => null, 'entityName' => $entityName]);
   }

   public static function entity(string $entityName) : Query {
      return new Query(trim($entityName));
   }

   public function join(string $entityName, string $localField, string $operator, string $foreignField) : Query {
      $entityName = trim($entityName);
      $localField = trim($localField);
      $operator = trim($operator);
      $foreignField = trim($foreignField);
      $this->ensureIsAValidOperator($operator);
      $this->ensureJoinKeysAreValid($localField, $foreignField);
      array_push($this->relationships, [
         'relationship' => self::JOIN, 
         'entityName' => $entityName, 
         'localField' => $localField,
         'operator' => $operator,
         'foreignField' => $foreignField
      ]);
      return $this;
   }

   private function ensureIsAValidOperator(string $operator) : void {
      if($operator != self::EQUAL && $operator != self::NOT_EQUAL){
         throw new InvalidArgumentException("No se reconoce el operador $operator en Query");
         
      }
   }

   public function leftjoin(string $entityName, string $localField, string $operator, string $foreignField) : Query {
      $this->ensureIsAValidOperator($operator);
      $this->ensureJoinKeysAreValid($localField, $foreignField);
      array_push($this->relationships, [
         'relationship' => self::LEFT_JOIN, 
         'entityName' => $entityName, 
         'localField' => $localField, 
         'operator' => $operator,
         'foreignField' => $foreignField
      ]);
      return $this;
   }

   public function withCriteria(Criteria $criteria) : Query {
      $this->criteria = $criteria;
      return $this;
   }

   public function select(string ... $fields) : Query {
      $this->ensureIsAValidaSelect($fields);
      $this->select = array_merge($this->select, $fields);
      return $this;
   }

   private function ensureIsAValidaSelect(array $selectFields) : void {
      foreach ($selectFields as $field) {
         if(!$this->isAValidTableAndField($field) && !$this->isAValidAsterisk($field))
            throw new InvalidArgumentException("El campo de la claúsula SELECT debe tener el formato 'entityName.fieldName' y se ha encontrado <$field>");    
      }
   }

   public function hasCriteria() : bool {
      return isset($this->criteria);
   }

   public function criteria() : Criteria {
      return $this->criteria;
   }

   /**
    * Devuelve la expresión de la claúsula SELECT en la posición $index
    */
   public function selectExpressionAt(int $index) : string {
      return $this->select[$index];
   }

   /**
    * Devuelve true si la expresión select en la posición $index es tipo '*'
    */
   public function selectIsAsteriskAt(int $index) : bool {
      return $this->select[$index] == '*';
   }

   /**
    * Devuelve true si la expresión select en la posición $index es tipo 'entidad.campo'
    */
   public function selectIsEntityAndFieldAt(int $index) : bool {
      $entityAndField = $this->cleanAlias($this->select[$index]);
      return preg_match('/^\w+\.\w+$/', $entityAndField, $output_array);
   }

   private function cleanAlias(string $expression) : string {
      return trim(explode(" as", $expression)[0]);
   }

   /**
    * Devuelve true si la expresión select en la posición $index es tipo 'entidad.*'
    */
   public function selectIsEntityAndAsteriskAt(int $index) : bool {
      $entityAndField = $this->cleanAlias($this->select[$index]);
      return preg_match('/^\w+\.\*$/',$entityAndField, $output_array);
   }

   public function isAValidAsterisk(string $field) : bool {
      if($field == '*')
         return true;
      return preg_match('/^\w+\.\*$/', $field, $output_array);
   }

   private function isAValidTableAndField(string $tableAndField) : bool {
      $tokens = explode(".", $tableAndField);
      return count($tokens) == 2;
   }

   private function ensureJoinKeysAreValid(string $localField, string $foreignField) : void {
      if(!$this->isAValidTableAndField($localField))
         throw new InvalidArgumentException("La clave local en JOIN debe tener el formato 'entityName.fieldName' y se ha encontrado <$localField>");    

      if(!$this->isAValidTableAndField($foreignField))
         throw new InvalidArgumentException("La clave externa en JOIN debe tener el formato 'entityName.fieldName' y se ha encontrado <$foreignField>");    

   }


   /**
    * Devuelve la primera entidad de la consulta
    */
   public function baseEntity() : string {
      return $this->relationships[0]['entityName'];
   }

   /**
    * Devuelve el número de relaciones involucradas en la consulta
    */
   public function relationshipsCounter() : int {
      return count($this->relationships);
   }

   /**
    * Devuelve el nombre de la entidad de la relación en la posición $index
    */
   public function entityNameAt(int $index) : string {
      return $this->relationships[$index]['entityName'];
   }

   /**
    * Devuelve el tipo de relación en la posición $index
    */
   public function relationshipAt(int $index) : string {
      return $this->relationships[$index]['relationship'];
   }

   /**
    * Devuelve el nombre de la entidad del campo local que se usa como clave en la relación $index 
    */
   public function localEntityNameAt(int $index) : string {
      $tokens = explode(".", $this->relationships[$index]['localField']);
      return $tokens[0];
   }

   /**
    * Devuelve el nombre del campo local que se usa como clave en la relación en la posición $index
    */
   public function localFieldNameAt(int $index) : string {
      $tokens = explode(".", $this->relationships[$index]['localField']);
      return $tokens[1];
   }

   /**
    * Devuelve el nombre de la entidad del campo que se usa como clave externa en la relación de la posición $index
    */
   public function foreignEntityNameAt(int $index) : string {
      $tokens = explode(".", $this->relationships[$index]['foreignField']);
      return $tokens[0];
   }

   /**
    * Devuelve el nombde del campo que se usa como clave externa en la relación de la posición $index
    */
   public function foreignFieldNameAt(int $index) : string {
      $tokens = explode(".", $this->relationships[$index]['foreignField']);
      return $tokens[1];
   }


   /**
    * Devuelve el operados usado en la relación de la posición $index
    */
   public function operatorAt(int $index) : string {
      return $this->relationships[$index]['operator'];
   }

   /**
    * Devuelve el numero de columnas del select
    */
   public function selectCounter() : int {
      return count($this->select);
   }

   /**
    * Devuelve el nombre de la entidad de la columna del select en la posición $index
    */
   public function selectEntityNameAt(int $index) : string {
      $tokens = explode(".", $this->select[$index]);
      return $tokens[0];
   }

   /**
    * Devuelve el nombde de la columnas del select en la posición $index
    */
   public function selectFieldNameAt(int $index) : string {
      //operador.nombre as alias1
      $tokens = explode(".", $this->select[$index]); //nombre as alias1
      $tokens = explode(" as", $tokens[1]); //[nombre, alias1]
      return trim($tokens[0]); //[nombre]
   }

   /**
    * Devuelve el alias de la columna en la posición $Index si lo tiene
    */
   public function selectFieldAliasAt(int $index) : ?string {
      $tokens = explode(" as", $this->select[$index]);
      if(count($tokens) == 2)
         return trim($tokens[1]);
      return null;
   }

}