<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'npk' => 'required',
            'unitkerja' => 'required',
            'nama_user' => 'required',
        ]);
        $user = User::create([
            'username' => $req->username,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'npk' => $req->npk,
            'unitkerja' => $req->unitkerja,
            'nama_user' => $req->nama_user,
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $req)
    {
        $req->validate([
            'npk' => 'required',
            'password' => 'required'
        ]);

        $credentials = request(['npk', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $req->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($req->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function user(Request $req)
    {
        $user = Auth::user();
        return response()->json(['data' => $user], 200);
    }

    public function logout(Request $req)
    {
        $req->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
