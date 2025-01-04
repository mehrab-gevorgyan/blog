<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
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

        return redirect()->route('/posts');
    }
}
