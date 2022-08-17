<?php

namespace Qalis\Shared\Domain\Subjects;

use InvalidArgumentException;
use Qalis\Shared\Domain\ValueObjects\StringValueObject;

class SubjectGender extends StringValueObject {

    public const MALE = 'male';
    public const FEMALE = 'female';
    public const OTHER_GENDER = 'other';

    public function __construct($value = null)
    {
        $this->ensureIsAValidaGender($value);
        parent::__construct($value);
    }

    private function ensureIsAValidaGender($value){
        if (($value != self::MALE) && ($value != self::FEMALE) && ($value != self::OTHER_GENDER)) {
            throw new InvalidArgumentException("No se reconoce el tipo $value como género del sujeto.");
        }
    }

}
