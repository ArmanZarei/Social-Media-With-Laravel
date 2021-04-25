<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('app')->accessToken;

            return \response([
                'token' => $token,
            ]);
        }

        return \response([
            'message' => 'Invalid username/password',
        ], Response::HTTP_UNAUTHORIZED);
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
}
