<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Support\CommonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $commonResponse = new CommonResponse();

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            $commonResponse->success('Registration successful', [
                'user'  => $user,
                'token' => $token
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('booking')->info('REGISTER_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function login(LoginRequest $request)
    {
        $commonResponse = new CommonResponse();

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $commonResponse
                    ->fail('Invalid credentials', null, CommonResponse::STATUS_CODE_UNAUTH)
                    ->commonApiResponse();
            }

            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $commonResponse->success('Login Successful', [
                'user'     => $user,
                'token'    => $token,
                'is_admin' => $user->is_admin,
            ]);
        } catch (Throwable $e) {
            Log::channel('booking')->info('LOGIN_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function logout()
    {
        $commonResponse = new CommonResponse();

        try {
            Auth::user()->currentAccessToken()->delete();
            $commonResponse->success('Logged out successfully');
        } catch (Throwable $e) {
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }
}
