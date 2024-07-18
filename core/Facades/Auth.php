<?php

namespace Core\Facades;

use App\Http\Models\User;

class Auth
{
    protected static $sessionKey = 'authenticated_user';

    public static function user(): ?User
    {
        self::startSession();
        if (isset($_SESSION[self::$sessionKey])) {
            return unserialize($_SESSION[self::$sessionKey]);
        }
        return null;
    }

    public static function setUser(User $user)
    {
        self::startSession();
        unset($user->password);
        $_SESSION[self::$sessionKey] = serialize($user);
    }

    public static function check(): bool
    {
        self::startSession();
        return isset($_SESSION[self::$sessionKey]);
    }

    public static function logout(): void
    {
        self::startSession();
        unset($_SESSION[self::$sessionKey]);
    }

    protected static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
