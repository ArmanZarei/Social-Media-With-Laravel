<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordController extends Controller
{
    public function forgot(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');

        if (User::where('email', $email)->doesntExist()) {
            return response([
                'message' => 'User doesn\'t exists',
            ], Response::HTTP_NOT_FOUND);
        }

        $token = Str::random(10);
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
        ]);

        Mail::send('mails.forgot_password', ['token' => $token], function (Message $message) use ($email) {
            $message->to($email);
            $message->subject('Reset your password');
        });

        return \response([
            'message' => "Done. Check your email",
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $token = $request->input('token');

        $password_reset = DB::table('password_resets')->where('token', $token)->first();
        if (!$password_reset) {
            return response([
                'message' => 'Invalid token!'
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = User::where('email', $password_reset->email)->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response([
            'message' => 'success',
        ]);
    }
}
