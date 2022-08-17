<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain;

use DateTimeImmutable;
use DateTimeInterface;
use ReflectionClass;
use RuntimeException;

final class Utils
{


    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    public static function stringToDate(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date);
    }

    public static function jsonEncode(array $values): string
    {
        return json_encode($values);
    }

    public static function jsonDecode(string $json): array
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }

        return $data;
    }

    public static function isBinary($value): bool
    {
        return false === mb_detect_encoding((string)$value, null, true);
    }

    public static function extractClassName(object $object): string
    {
        $reflect = new ReflectionClass($object);

        return $reflect->getShortName();
    }

}
