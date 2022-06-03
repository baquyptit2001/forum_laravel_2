<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('authtoken')->plainTextToken;
            return response()->json(['token' => $token, 'user' => UserResource::make($user), 'message' => 'Đăng nhập thành công'], 200);
        } else {
            return response()->json(['error' => 'Tài khoản hoặc mật khẩu không đúng'], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Đăng xuất thành công'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Đăng xuất thất bại',
                'error' => $error,
            ]);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ]);
    }

    public function register(SignupRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json(['message' => 'Đăng ký thành công', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đăng ký thất bại'], 500);
        }
    }
}
