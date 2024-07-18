<?php

namespace Core\Encryption;


class Hash
{
    /**
     * Hash a password using password default.
     *
     * @param string $password
     * @return string
     */
    public static function make(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify a password against a hashed password.
     *
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    public static function verify(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
