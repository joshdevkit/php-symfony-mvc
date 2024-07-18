<?php

namespace Core;

class View
{
    protected static $data = [];
    protected static $layout = null;
    protected static $sections = [];
    protected static $sectionStack = [];
    protected static $pageTitle = '';

    public static function make($view, $data = [])
    {
        return new static($view, $data);
    }

    public static function render($view, $data = [])
    {
        self::$data = $data;
        extract($data);

        $viewPath = self::getViewFilePath($view);

        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
        } else {
            $content = self::handleError("View file '{$viewPath}' not found.");
        }

        if (self::$layout) {
            $layoutPath = self::getViewFilePath(self::$layout);

            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                $layoutContent = ob_get_clean();
                $rendered = str_replace('{{ content }}', $content, $layoutContent);
            } else {
                $rendered = self::handleError("Layout file '{$layoutPath}' not found.");
            }
        } else {
            $rendered = $content;
        }

        $rendered = str_replace('{{ title }}', self::$pageTitle, $rendered);

        echo $rendered;
    }

    public static function extend($layout)
    {
        self::$layout = $layout;
    }

    protected static function getViewFilePath($view)
    {
        return RESOURCE_PATH . str_replace('.', '/', $view) . '.php';
    }

    protected static function handleError($errorMessage)
    {
        ob_start();
        include self::getViewFilePath('error', compact('errorMessage'));
        return ob_get_clean();
    }

    public static function startSection($section)
    {
        self::$sectionStack[] = $section;
        ob_start();
    }

    public static function stopSection()
    {
        $last = array_pop(self::$sectionStack);
        self::$sections[$last] = ob_get_clean();
    }

    public static function yieldSection($section)
    {
        if (isset(self::$sections[$section])) {
            echo self::$sections[$section];
        }
    }

    public static function setPageTitle($title)
    {
        self::$pageTitle = $title;
    }
}
