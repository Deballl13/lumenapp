<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function register (Request $request) {
        User::create([
            'nama' => htmlspecialchars(trim($request->nama)),
            'email' => htmlspecialchars(trim($request->email)),
            'no_hp' => htmlspecialchars(trim($request->no_hp)),
            'password' => Hash::make(htmlspecialchars($request->password))
        ]);

        return response()->json(['message' => 'Pendaftaran berhasil']);
    }
    
    public function login(Request $request) {
        $email = htmlspecialchars(trim($request->email));
        $password = htmlspecialchars($request->password);

        // cek email dan password inputan dengan email di database
        $customer = User::where('email', $email)->first();
        if (!$customer || !Hash::check($password, $customer->password)) {
            return response()->json(['message' => 'Email/Password salah'], 400);
        }

        if($customer->token !== null){
            return response()->json(['message' => 'Anda telah terhubung diperangkat lain'], 403);
        }

        // generate token
        $generateToken = bin2hex(random_bytes(40));
        $customer->update([
            'token' => $generateToken
        ]);

        return response()->json(['user' => $customer, 'message' => 'Login berhasil']);
    }

    public function logout(Request $request) {
        $user_token = $request->header('Authorization');

        User::where('token', $user_token)->update([
            'token' => null
        ]);

        return response()->json(['message' => 'Logout berhasil']);
    }
}
