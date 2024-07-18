<?php

namespace Core;

class Redirect
{
    protected $url;

    public static function back()
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        return new static($url);
    }

    public function __construct($url = null)
    {
        $this->url = $url;
    }

    public function with($key, $value)
    {
        $_SESSION[$key] = $value;
        header("Location: " . $this->url);
        exit;
    }

    public static function to($url)
    {
        header("Location: " . $url);
        exit;
    }
}
