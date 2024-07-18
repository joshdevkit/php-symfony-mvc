<?php


namespace Core\Exceptions;


class MethodNotFound
{
    public function __invoke($method, $class)
    {
        return view('error', compact('method', 'class'));
    }
}
