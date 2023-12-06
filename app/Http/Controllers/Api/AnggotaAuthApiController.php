<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaAuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('anggota')->attempt($credentials)) {
            $user = Auth::guard('anggota')->user();
            $token = $user - createToken('AnggotaToken')->accessToken;

            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user('anggota')->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
