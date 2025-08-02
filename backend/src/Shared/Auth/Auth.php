<?php
namespace SalesAppApi\Shared\Auth;

class Auth
{
    private static $user = null;

    public static function setUser(array $userData): void
    {
        self::$user = $userData;
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function id(): ?int
    {
        return self::$user['id'] ?? null;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }
}
