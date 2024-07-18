<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index($id)
    {
        echo "User with Params {$id}";
    }

    public function about()
    {
        echo "This is the about page.";
    }

    public function profile()
    {
        return view('user.profile');
    }
}
