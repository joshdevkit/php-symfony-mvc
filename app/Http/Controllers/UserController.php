<?php

namespace App\Http\Controllers;

use App\Http\Models\User;
use Core\Databases\DB;
use Core\Encryption\Hash;
use Core\Exceptions\ValidationException;
use Core\Facades\Auth;
use Core\Request;
use Core\Validation\Validator;

class UserController extends Controller
{
    public function show($id)
    {
        echo "User with Params {$id}";
    }

    public function about()
    {
        echo "This is the about page.";
    }

    public function profile()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email'
        ];

        if (!empty($request->input('newPassword'))) {
            $rules['currentPassword'] = 'required';
            $rules['newPassword'] = 'required|min:8|confirmed';
        }

        try {
            $validator = new Validator();
            $validator->validate($request->all(), $rules);
            $userId = Auth::user()->id;
            $user = User::find($userId);
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            if (!empty($request->input('newPassword'))) {
                $user->password = Hash::make($request->input('newPassword'));
            }

            $user->save();

            return redirect()->back()->with('message', 'Profile has been Updated');
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $oldInput = $request->all();
            $user = Auth::user();
            return view('user.profile', compact('errors', 'oldInput', 'user'));
        }
    }



    public function user_info(Request $request)
    {
        $users = DB::table('user_info')
            ->join('users', 'user_info.user_id', '=', 'users.id')
            ->select([
                'user_info.id',
                'user_info.contact',
                'user_info.address',
                'user_info.citizenship',
                'user_info.profile_picture',
                'users.name',
                'users.email'
            ]);

        return view('user.index', compact('users'));
    }
}
