<?php
namespace SalesAppApi\Shared\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService
{
    private static string $algo = 'HS256';

    public static function generateToken(array $payload): string
    {
        $secret = $_ENV['JWT_SECRET'];
        $ttl    = $_ENV['JWT_TTL'] ?: 3600;

        $time = time();

        $tokenPayload = array_merge($payload, [
            'iat' => $time,
            'exp' => $time + $ttl,
        ]);

        return JWT::encode($tokenPayload, $secret, self::$algo);
    }

    public static function validateToken(string $token): ?array
    {
        $secret = $_ENV['JWT_SECRET'];

        try {
            $decoded = JWT::decode($token, new Key($secret, self::$algo));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
