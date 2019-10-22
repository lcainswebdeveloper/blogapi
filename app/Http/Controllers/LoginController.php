<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (Auth::attempt($request->only('email', 'password'))):
            $user = Auth::user();
        Auth::login($user);
        $user->api_token = str_random(60);
        $user->save();
        $user->token = $user->api_token;
        return response()->json($user, 200);
        endif;
        return response()->json('Not Authorised', 401);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
    }

    public function verify(Request $request)
    {
        $check = User::whereId($request->user_id)->whereApiToken($request->api_token)->first();
        if ($check):
            Auth::login($check);
        $user = Auth::user();
        return response()->json($user, 200);
        endif;

        return response()->json('Not Authorised', 401);
    }
}
