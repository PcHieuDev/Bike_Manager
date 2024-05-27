<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;



class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            if (Auth::attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password
                ]
            )) {
                $user = Auth::user();
                $token = $user->createToken('token')->accessToken;

                return response()->json(['token' => $token, 'user' => $user], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function register(Request $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json(['message' => 'User created'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json(['message' => 'User logged out'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Throw the exception again so it can be handled by the exception handler
            throw $e;
        }
    }
}
