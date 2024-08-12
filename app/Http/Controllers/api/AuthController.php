<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'admin' => $admin,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }
}
