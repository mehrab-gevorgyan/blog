<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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

        return redirect()->route('/posts');
    }
}
