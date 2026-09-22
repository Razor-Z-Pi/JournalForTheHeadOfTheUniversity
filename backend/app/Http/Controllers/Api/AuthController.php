<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Авторизация старосты
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Неверный логин или пароль'
            ], 401);
        }

        $user = Auth::user();
        $token = $user -> createToken('auth_token') -> plainTextToken;

        return response() -> json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response() -> json([
            'message' => 'Вы вышли из системы!!!'
        ]);
    }
}