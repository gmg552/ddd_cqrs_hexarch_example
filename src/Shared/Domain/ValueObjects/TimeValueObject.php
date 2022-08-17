<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use DateTime;

class TimeValueObject extends DateTime
{

    const DEFAULT_DATE = "1900-01-01";

    public const DEFAULT_FORMAT = "H:i:s";
   

    public function __construct($time)
    {
        parent::__construct(self::DEFAULT_DATE.' '.$time, null);
    }

    public function __toString() : string {
        return self::toString($this);
    }

    public static function toString(TimeValueObject $time = null) : ?string {
        return $time ? $time->format(self::DEFAULT_FORMAT) : null;
    }
    

}
