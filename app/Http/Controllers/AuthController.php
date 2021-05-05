<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $cookie_key = 'jwt';

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie($this->cookie_key, $token, 60 * 24); // 1 day

        return response([
            'message' => $token
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return Auth::user();
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'firstname' => $request->input('first_name'),
            'lastname' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return $user;
    }

    public function logout()
    {
        $cookie = Cookie::forget($this->cookie_key);

        return response([
            'message' => 'Success',
        ])->withCookie($cookie);
    }
}
