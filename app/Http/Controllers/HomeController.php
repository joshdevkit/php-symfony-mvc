<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use Core\Facades\Auth;
use Core\View;

class HomeController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('index', compact('users'));
    }
}
