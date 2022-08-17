<?php
declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use Qalis\Certification\Audits\Domain\Exceptions\InvalidAuditNotes;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class AuditNotes extends StringValueObject
{

   public const MAX_LENGTH = 10000;

   public function __construct(string $value)
   {
      $this->ensureLengthIsLowerThanLimit($value);
      $this->value = $value;
   }

   private function ensureLengthIsLowerThanLimit(string $value): void
   {
      if (strlen($value) > self::MAX_LENGTH)
         throw InvalidAuditNotes::invalidLength(self::MAX_LENGTH);
   }
}
