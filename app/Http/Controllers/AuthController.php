<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $message = [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email salah',
            'password.required' => 'Password tidak boleh kosong'
        ];

        $validator = Validator::make($request->all(), $validate, $message);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password yang anda masukkan salah'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token tidak terbuat'], 500);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'data'    => $user,
            'token'   => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $validate = [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ];

        $message = [
            'name.required' => 'Nama tidak boleh kosong',
            'name.between' => 'Nama harus lebih dari 2 karakter dan kurang dari 100 karakter',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email salah',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password tidak boleh kosong',
            'password.confirmed' => 'Password dan Konfirmasi Password tidak sama',
            'password.required' => 'Password harus lebih dari 8 karakter',
        ];

        $validator = Validator::make($request->all(), $validate, $message);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dibuat',
            'data' => User::find($user->id)
        ], 201);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
