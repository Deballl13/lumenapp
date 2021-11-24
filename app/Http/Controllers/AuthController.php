<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    
    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;

        // cek email inputan dengan email di database
        $user = User::where('email', $email)->get();
        if (!$user) {
            return response()->json(['message' => 'Username/Password salah'], 401);
        }

        // cek password inputan dengan password di database
        $isValidPassword = Hash::check($password, $user->password);
        if (!$isValidPassword) {
            return response()->json(['message' => 'Username/Password salah'], 401);
        }

        // generate token
        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken
        ]);

        return response()->json($user);
    }

    public function logout() {
        $user = new Customer();
        $user->token = null;
        $user->save();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
