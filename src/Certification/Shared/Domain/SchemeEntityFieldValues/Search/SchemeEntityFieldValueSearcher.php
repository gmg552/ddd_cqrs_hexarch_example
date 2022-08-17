<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\Search;

use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set\SetSchemeEntityFieldValueCommand;
use Qalis\Certification\Shared\Domain\EntityFields\EntityFieldName;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldValidation;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\SchemeEntityFieldValue;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\SchemeEntityFieldValues;
use function Lambdish\Phunctional\map;

class SchemeEntityFieldValueSearcher
{

   private SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository;
   private SchemeEntityFieldValues $fieldValues;
   public function __construct(SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository)
   {
      $this->schemeEntityFieldReadRepository = $schemeEntityFieldReadRepository;
      $this->fieldValues = new SchemeEntityFieldValues();
   }

   public function __invoke(SetSchemeEntityFieldValueCommand ...$entityFieldValueCommands): SchemeEntityFieldValues
   {
      $entityFieldIdArray = map(static fn (SetSchemeEntityFieldValueCommand $command) => new SchemeEntityFieldId($command->id()), $entityFieldValueCommands);

      $entityFields = $this->schemeEntityFieldReadRepository->find(...$entityFieldIdArray);

      foreach ($entityFields as $entityField) {
         $fieldValueCommand = null;
         foreach ($entityFieldValueCommands as $setEntityFieldValueCommand) {
            if ($setEntityFieldValueCommand->id() == $entityField->id()) {
               $fieldValueCommand = $setEntityFieldValueCommand;
            }
         }
         if ($fieldValueCommand) {
            $this->fieldValues->push(new SchemeEntityFieldValue(
               new SchemeEntityFieldId($entityField->id()),
               new EntityFieldName($entityField->name()),
               $entityField->validation() ?? new SchemeEntityFieldValidation($entityField->validation()),
               $fieldValueCommand->value()
            ));
         }
      }

      return $this->fieldValues;
   }

}
