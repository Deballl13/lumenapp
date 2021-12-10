<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller {
    
    public function ubahPassword(Request $request){
        User::whereToken(auth()->guard('api')->user()->token)->update([
            'password' => Hash::make(htmlspecialchars($request->password))
        ]); 

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function ubahProfil(Request $request){
        User::whereToken(auth()->guard('api')->user()->token)->update([
            'nama' => htmlspecialchars(trim($request->nama)),
            'no_hp' => htmlspecialchars(trim($request->no_hp))
        ]);

        return response()->json(['message' => 'Profil berhasil diubah']);
    }
}