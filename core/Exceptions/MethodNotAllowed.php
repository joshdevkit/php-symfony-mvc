<?php

namespace Core\Exceptions;


class MethodNotAllowed
{
    public function __invoke($MethodNotAllowed)
    {

        return view('error', compact('MethodNotAllowed'));
    }
}
