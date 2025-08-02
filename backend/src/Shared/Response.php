<?php
namespace SalesAppApi\Shared;

class Response
{
    public static function json(array $arrayObject, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($arrayObject, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function html(string $file, array $arrayObject = [], int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
        extract($arrayObject);
        require $file;
        exit;
    }
}
