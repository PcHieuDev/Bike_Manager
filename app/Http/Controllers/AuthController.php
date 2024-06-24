<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use App\Exceptions\SomethingHasErrorException;
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
        if (!Auth::attempt($request->only('email', 'password'))) { //  nếu không thể xác thực người dùng với thông tin được cung cấp
            return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user(); // Lấy thông tin người dùng đã xác thực   
        $token = $user->createToken('token')->accessToken; // Tạo token cho người dùng đã xác thực  

        return response()->json(['token' => $token, 'user' => $user], Response::HTTP_OK);
    }


    public function register(Request $request)
    {
        try {
            $this->userRepository->create([ // Tạo mới người dùng
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json(['message' => 'User created'], 201);
        } catch (\Exception $e) {
            throw new SomethingHasErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke(); // Revoke the token on logout
            return response()->json(['message' => 'User logged out'], Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new SomethingHasErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
