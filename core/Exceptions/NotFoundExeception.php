<?php


namespace Core\Exceptions;


class NotFoundExeception
{
    public function __invoke()
    {
        $content = "page not found";
        return view('error', compact('content'));
    }
}
