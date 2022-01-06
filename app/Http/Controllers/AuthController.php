<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function register (Request $request) {
        // cek data
        $user = User::whereEmail(htmlspecialchars(trim($request->email)))->first();

        // jika akun sudah ada
        if(!$user){
            User::create([
                'nama' => htmlspecialchars(trim($request->nama)),
                'email' => htmlspecialchars(trim($request->email)),
                'no_hp' => htmlspecialchars(trim($request->no_hp)),
                'password' => Hash::make(htmlspecialchars($request->password))
            ]);

            return response()->json(['message' => 'Pendaftaran berhasil']);
        }
        // jika akun belum ada
        else{
            return response()->json(['message' => 'Email kamu sudah terdaftar'], 400);
        }        
    }
    
    public function login(Request $request) {
        $email = htmlspecialchars(trim($request->email));
        $password = htmlspecialchars($request->password);

        // cek email dan password inputan dengan email di database
        $user = User::whereEmail($email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Email/Password salah'], 400);
        }

        // cek token null atau tidak
        if($user->token !== null){
            return response()->json(['message' => 'Kamu telah terhubung diperangkat lain'], 403);
        }

        // generate token
        $user->update([
            'token' => bin2hex(random_bytes(40))
        ]);

        return response()->json(['user' => $user, 'message' => 'Login berhasil']);
    }

    public function logout() {
        // update token
        User::whereToken(auth()->guard('api')->user()->token)->update([
            'token' => null
        ]);
        return response()->json(['message' => 'Logout berhasil']);
    }
}
