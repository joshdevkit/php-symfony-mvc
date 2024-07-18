<?php

namespace Core\Facades;

use App\Http\Models\User;

class Auth
{
    protected static $sessionKey = 'authenticated_user';

    public static function user(): ?User
    {
        self::startSession(); // Ensure session is started
        if (isset($_SESSION[self::$sessionKey])) {
            return unserialize($_SESSION[self::$sessionKey]);
        }
        return null;
    }

    public static function setUser(User $user)
    {
        self::startSession(); // Ensure session is started
        $_SESSION[self::$sessionKey] = serialize($user);
    }

    public static function check(): bool
    {
        self::startSession(); // Ensure session is started
        return isset($_SESSION[self::$sessionKey]);
    }

    public static function logout(): void
    {
        self::startSession(); // Ensure session is started
        unset($_SESSION[self::$sessionKey]);
    }

    protected static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
