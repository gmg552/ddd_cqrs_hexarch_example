<?php

namespace Qalis\Certification\Shared\Domain\AuditOperatorRepresentatives;

use InvalidArgumentException;
use Qalis\Certification\AuditOperatorRepresentatives\Domain\AuditOperatorRepresentative;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class AuditOperatorRepresentativeType extends StringValueObject
{
    public function __construct($value = null)
    {
        $this->ensureIsValid($value);
        parent::__construct($value);
    }

    private function ensureIsValid($value){
        if (($value !== AuditOperatorRepresentative::MANAGEMENT_TYPE) && ($value !== AuditOperatorRepresentative::OTHER_TYPE)) {
            throw new InvalidArgumentException("No se reconoce el tipo $value del representante.");
        }
    }
}
