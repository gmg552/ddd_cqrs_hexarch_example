<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFields\Search;


class SchemeEntityFieldResponse
{

   private string $id;
   private string $entityName;
   private string $name;
   private string $label;
   private string $type;


   private ?array $stringValues;
   private ?int $length;
   private ?array $condition;
   private ?array $validation;


   public function __construct(
       string $id,
       string $entityName,
       string $name,
       string $label,
       string $type,
       array $stringValues = null,
       int $length = null,
       array $condition = null,
       array $validation = null
   )
   {
      $this->id = $id;
      $this->entityName = $entityName;
      $this->name = $name;
      $this->label = $label;
      $this->type = $type;
      $this->stringValues = $stringValues;
      $this->length = $length;
      $this->condition = $condition;
      $this->validation = $validation;

   }

   public function id(): string
   {
      return $this->id;
   }

   public function label(): string
   {
      return $this->label;
   }

   public function type(): string
   {
      return $this->type;
   }

   public function name(): string
   {
      return $this->name;
   }

   public function entityName(): string
   {
      return $this->entityName;
   }

   public function validation(): ?array
   {
      return $this->validation;
   }

   public function condition(): ?array
   {
      return $this->condition;
   }

   public function stringValues() : ?array {
      return $this->stringValues;
   }

   public function toArray(): array
   {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'entityName' => $this->entityName,
        'label' => $this->label,
        'type' => $this->type,
        'length' => $this->length,
        'validation' => $this->validation,
        'condition' => $this->condition,
        'stringValues' => $this->stringValues
      ];
   }
}
