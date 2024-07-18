<?php

namespace Core\Exceptions;


class ViewNotFound
{
    public static function propagate($view)
    {
        return view('error', compact('view'));
    }
}
