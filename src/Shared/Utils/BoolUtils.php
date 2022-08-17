<?php

namespace Qalis\Shared\Utils;

final class BoolUtils {

    static public function isValid($value): bool {
        return $value == 1 || $value == 0;
    }

    static public function toBoolOrNull($value = null): ?bool{
        if($value === null)
            return null;
        return (bool) $value;
    } 

}
