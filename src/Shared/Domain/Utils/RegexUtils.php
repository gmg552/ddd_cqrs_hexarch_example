<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Utils;

final class RegexUtils
{

    public static function extractFunctions(string $expression): array {
        preg_match_all("/[a-zA-Z]+\((([^()]*|(?R))*)\)/i", $expression, $results);
        return count($results) ? $results[0] : [];
    }

    public static function extractFunctionName(string $function): string {
        preg_match_all("/([a-zA-Z]*)\(/i", $function, $results);
        return count($results) ? $results[1][0] : '';
    }

    public static function extractFunctionArgs(string $function): array {
        preg_match_all("/[a-zA-Z]+\((([^()]*|(?R))*)\)/i", $function, $results);
        return count($results) ? self::removeSpacesAndQuotes($results) : [];
    }

    private static function removeSpacesAndQuotes(array $results): array {
        $list = explode(",", $results[1][0]);
        foreach($list as $key => $item) {
            $list[$key] = trim($item, " ");
            $list[$key] = trim($list[$key], "'");
        }
        return $list;
    }

    public static function isAFunction(string $arg): bool {
        return (!empty(self::extractFunctions($arg)));
    }

}
