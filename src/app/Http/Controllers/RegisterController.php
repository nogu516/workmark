<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request)
    {
        // バリデーションは自動で実行されます
        $validated = $request->validated();

        // 登録処理など
    }
}
