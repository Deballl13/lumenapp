<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function register (Request $request) {
        $customer = new User();
        $customer->nama = trim($request->nama);
        $customer->email = trim($request->email);
        $customer->no_hp = trim($request->no_hp);
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json(['message' => 'Pendaftaran berhasil']);
    }
    
    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;

        // cek email inputan dengan email di database
        $customer = User::where('email', $email)->first();
        if (!$customer) {
            return response()->json(['message' => 'Email/Password salah'], 401);
        }

        // cek password inputan dengan password di database
        $isValidPassword = Hash::check($password, $customer->password);
        if (!$isValidPassword) {
            return response()->json(['message' => 'Email/Password salah'], 401);
        }

        // generate token
        $generateToken = bin2hex(random_bytes(40));
        $customer->update([
            'token' => $generateToken
        ]);

        return response()->json(['user' => $customer, 'message' => 'Login berhasil']);
    }

    public function logout() {
        $user_token = auth()->guard('api')->user()->token;

        $customer = User::where('token', $user_token)->first();
        $customer->token = null;
        $customer->save();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
