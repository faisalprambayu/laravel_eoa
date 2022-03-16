<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        $passwordliat = Hash::make($request->password);
        // dd($passwordliat);
        if (!$user || ! Hash::check($request->password, $user->password)) {
            dd(Hash::check($request->password, $user->password));
            return response()->json([
                'succes' => false,
                'message' => 'Unauthorized'
            ],401);
        }

        $user->tokens()->delete();
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'success',
            'user' => $user,
            'token' => $token,
        ],200);
    }
}
