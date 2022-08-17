<?php

namespace Qalis\Shared\Utils;

use DateTime;
use InvalidArgumentException;

class DateUtils
{

    private CONST DEFAULT_TIME = '00:00:00';
    private CONST DEFAULT_LAST_TIME = '23:59:59';
    private CONST DEFAULT_DATE_FORMAT = 'Y-m-d';

    static public function dateToDateTime(string $date, ?bool $isGreaterThan = false): string {
        self::ensureValidDate($date);
        return $isGreaterThan ? ($date.' '.self::DEFAULT_LAST_TIME) : ($date.' '.self::DEFAULT_TIME);
    }

    static private function ensureValidDate(string $date): void{
        $dt = DateTime::createFromFormat(self::DEFAULT_DATE_FORMAT, $date);
        if (!$dt || !($dt->format(self::DEFAULT_DATE_FORMAT) === $date))
            throw new InvalidArgumentException("La fecha <$date> no tiene el formato correcto.");
    }

}
