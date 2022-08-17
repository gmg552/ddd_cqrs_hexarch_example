<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFieldValues;

use Qalis\Certification\Shared\Domain\EntityFields\EntityFieldName;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldValidation;

class SchemeEntityFieldValue
{

   private SchemeEntityFieldId $schemeEntityFieldId;
   private ?SchemeEntityFieldValidation $validation;
   private EntityFieldName $fieldName;
   private $value;

   public function __construct(SchemeEntityFieldId $schemeEntityFieldId, EntityFieldName $fieldName, ?SchemeEntityFieldValidation $validation , $value = null)
   {
      $this->ensureIsValid($value);
      $this->schemeEntityFieldId = $schemeEntityFieldId;
      $this->validation = $validation;
      $this->fieldName = $fieldName;
      $this->value = $value;
   }


   public function schemeEntityFieldId() : SchemeEntityFieldId
   {
      return $this->schemeEntityFieldId;
   }

   public function value()
   {
      return $this->value;
   }

   public function validation() : ?SchemeEntityFieldValidation {
      return $this->validation;
   }

   public function fieldName() : EntityFieldName{
      return $this->fieldName;
   }

   private function ensureIsValid($value){
      //TODO
   }
}
