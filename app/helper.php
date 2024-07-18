<?php

use Core\View;
use Core\Redirect;

if (!function_exists('extend')) {
    function extend($layout)
    {
        View::extend($layout);
    }
}

if (!function_exists('view')) {
    function view(string $view, array $data = [])
    {
        return View::render($view, $data);
    }
}

if (!function_exists('asset')) {
    function asset($path)
    {
        return BASE_URL . '/' . trim($path, '/');
    }
}

if (!function_exists('title')) {
    function title($pageTitle)
    {
        View::setPageTitle($pageTitle);
    }
}

if (!function_exists('redirect')) {
    function redirect()
    {
        return new Redirect();
    }
}

if (!function_exists('to')) {
    function to($url)
    {
        Redirect::to($url);
    }
}
