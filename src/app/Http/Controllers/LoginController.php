<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/home'); // ログイン成功時
        }

        return back()->withErrors([
            'auth' => 'ログイン情報が登録されていません',
        ])->withInput();
    }
}
