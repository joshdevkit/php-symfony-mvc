<?php


namespace Core\Exceptions;


class NotFoundException
{
    public function __invoke()
    {
        $content = "page not found";
        return view('error', compact('content'));
    }
}
