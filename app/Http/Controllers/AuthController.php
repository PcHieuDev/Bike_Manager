<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use App\Exceptions\ErrorRegisteringUserException;
use App\Exceptions\ErrorLoginException;
use App\Exceptions\ErrorLogoutException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Traits\HttpResponseCode;



class AuthController extends Controller
{
    use HttpResponseCode;

    protected $userRepository;


    public function __construct( UserRepositoryInterface $userRepository   )
    {
      
        $this->userRepository = $userRepository;

    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->accessToken;

        return response()->json(['token' => $token, 'user' => $user], Response::HTTP_OK);
    }


    public function register(Request $request)
    {
        try {
            $this->userRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json(['message' => 'User created'], 201);
        } catch (\Exception $e) {
            throw new ErrorRegisteringUserException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json(['message' => 'User logged out'], Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new ErrorLogoutException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
