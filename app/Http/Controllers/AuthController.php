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
        $customer = Customer::where('email', $email)->first();
        if (!$customer) {
            return response()->json(['message' => 'Username/Password salah'], 401);
        }

        // cek password inputan dengan password di database
        $isValidPassword = Hash::check($password, $customer->password);
        if (!$isValidPassword) {
            return response()->json(['message' => 'Username/Password salah'], 401);
        }

        // generate token
        // $generateToken = bin2hex(random_bytes(40));
        // $user->update([
        //     'token' => $generateToken
        // ]);

        return response()->json(['customer' => $customer, 'message' => 'Login berhasil']);
    }

    public function logout() {
        $customer = new Customer();
        $customer->token = null;
        $customer->save();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
