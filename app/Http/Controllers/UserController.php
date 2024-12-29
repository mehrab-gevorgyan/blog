<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(UserRequest $request)
    {
        $user = new User();
        $user->login = $request->login;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->user_image = 'img/avatar.png';

        if($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $user->image = 'storage/user-images/'.$imageName;
            $request->image->move(storage_path('app/public/user-images'), $imageName);
        }
        $user->save();

        Auth::login($user);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['email', 'required'],
            'password' => ['required'],
        ]);

        $email = $request->email;
        $password = $request->password;

        if(Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect()->route('posts.index');
        }

        return back()->withInput()->withErrors([
            'loginError' => 'Invalid email or password',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
