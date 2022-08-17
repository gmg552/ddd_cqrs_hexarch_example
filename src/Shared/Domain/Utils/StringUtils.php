<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Utils;

final class StringUtils
{
    public static function endsWith(string $needle, string $haystack): bool
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function arrayToSnakeCase(array $array) : array {
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[self::toSnakeCase($key)] = $value;
        }
        return $newArray;
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', "$1_$2", $text));
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }

}
