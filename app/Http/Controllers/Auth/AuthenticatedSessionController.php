<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Models\User;
use Core\Encryption\Hash;
use Core\Exceptions\ValidationException;
use Core\Facades\Auth;
use Core\Redirect;
use Core\Request;
use Core\Validation\Validator;

class AuthenticatedSessionController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function execute(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        try {
            $validator = new Validator();
            $validator->validate($request->all(), $rules);

            $email = $request->input('email');
            $password = $request->input('password');

            $user = User::findOne('email', $email);

            if ($user && password_verify($password, $user->password)) {
                Auth::setUser($user);
                return redirect()->to('/');
            } else {
                $errors = [
                    'email' => 'These credentials do not match our records.'
                ];
                $oldInput = $request->all();
                return view('auth.login', compact('errors', 'oldInput'));
            }
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $oldInput = $request->all();
            return view('auth.login', compact('errors', 'oldInput'));
        }
    }




    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|confirm_password'
        ];

        try {
            $validator = new Validator();
            $validator->validate($request->all(), $rules);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);
            Auth::setUser($user);
            if ($user) {
                return redirect()->to('/');
            }
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $oldInput = $request->all();
            return view('auth.register', compact('errors', 'oldInput'));
        }
    }

    public function destroy(Request $request)
    {
        if ($request->isPost()) {
            Auth::logout();
            return redirect()->to('/');
        }
    }
}
