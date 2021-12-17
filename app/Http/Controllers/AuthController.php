<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function register (Request $request) {
        // insert data
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
        $customer = User::whereEmail($email)->first();
        if (!$customer || !Hash::check($password, $customer->password)) {
            return response()->json(['message' => 'Email/Password salah'], 400);
        }

        // cek token null atau tidak
        if($customer->token !== null){
            return response()->json(['message' => 'Anda telah terhubung diperangkat lain'], 403);
        }

        // generate token
        $customer->update([
            'token' => bin2hex(random_bytes(40))
        ]);

        return response()->json(['user' => $customer, 'message' => 'Login berhasil']);
    }

    public function logout() {
        // update token
        User::whereToken(auth()->guard('api')->user()->token)->update([
            'token' => null
        ]);
        return response()->json(['message' => 'Logout berhasil']);
    }
}
