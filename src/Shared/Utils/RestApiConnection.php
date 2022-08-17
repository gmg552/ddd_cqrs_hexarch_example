<?php

declare(strict_types=1);

namespace Qalis\Shared\Utils;

use DateTimeImmutable;
use DateTimeInterface;
use ReflectionClass;
use RuntimeException;

class RestApiConnection
{

    public const JSON_CONTENT_TYPE = 'Content-Type: application/json';

    public static function connect(string $url, string $contentType, array $header = null, array $data = null): ?array {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header ? $header : [$contentType]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);

    }

}
