<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use DateTime;
use DateTimeZone;

class DateValueObject extends DateTime
{

    public const DATETIME_DEFAULT_FORMAT = "Y-m-d";

    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
    }

    public function __toString() : string {
        return self::toString($this);
    }

    public static function toString(DateValueObject $date) : ? string {
        return $date ? $date->format(self::DATETIME_DEFAULT_FORMAT) : null;
    }

    public function year() : string {
        return $this->format('Y');
    }

    public function smallYear() : string {
        return $this->format('y');
    }

    public function equals(DateTime $other){
        return $this->getTimestamp() == $other->getTimestamp();
    }

    public function isGreaterThan(DateTime $other){
        return $this->getTimestamp() > $other->getTimestamp();
    }

    public function isGreaterOrEqualThan(DateTime $other){
        return $this->getTimestamp() >= $other->getTimestamp();
    }

    public function isLessThan(DateTime $other){
        return $this->getTimestamp() < $other->getTimestamp();
    }

    public function isLessOrEqualThan(DateTime $other){
        return $this->getTimestamp() <= $other->getTimestamp();
    }
}
