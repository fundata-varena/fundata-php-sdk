<?php

namespace Varena\SDK\Response;

class Response
{
    const SUCCESS = 200;

    const PARAMETERS_ERROR = 10000;

    const NO_DATA = 10001;

    const INTERNAL_ERROR = 10002;

    public static function successJsonResponse(array $data, string $message = ''): array
    {
        return self::jsonResponse(self::SUCCESS, $data, $message);
    }

    public static function errorJsonResponse(string $message, int $code): array
    {
        return self::jsonResponse($code, [], $message);
    }

    private static function jsonResponse(int $status, array $data, string $message): array
    {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ];
    }

    public static function isJson(): bool
    {
        return isset($_SERVER['HTTP_X_RESPONSE_TYPE']) && strtoupper($_SERVER['HTTP_X_RESPONSE_TYPE']) === 'JSON';
    }
}
