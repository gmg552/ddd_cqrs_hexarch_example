<?php

namespace Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Search;


class SchemeEntityFieldValueResponse
{

   private string $id;
   private string $label;
   private string $type;
   private string $name;
   private ?array $stringValues;
   private ?array $validation;
   private ?array $condition;
   private $value;

   public function __construct(string $id, string $label, string $type, string $name, ?array $stringValues, $value = null)
   {
      $this->id = $id;
      $this->label = $label;
      $this->type = $type;
      $this->name = $name;
      $this->stringValues = $stringValues;
      $this->value = $value;
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


   public function validation(): ?array
   {
      return $this->validation;
   }

   public function stringValues(): ?array
   {
      return $this->stringValues;
   }

   public function value()
   {
      return $this->value;
   }

   public function toArray(): array
   {
      return [
         'id' => $this->id,
         'type' => $this->type,
         'label' => $this->label,
         'value' => $this->value,
         'stringValues' => $this->stringValues
      ];
   }
}
