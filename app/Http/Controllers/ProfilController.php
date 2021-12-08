<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller {
    
    public function ubahPassword(Request $request){
        $user_token = $request->header('Authorization');

        if($user_token !== null){
            User::where('token', $user_token)->update([
                'password' => Hash::make(htmlspecialchars($request->password))
            ]); 

            return response()->json(['message' => 'Password berhasil diubah']);
        }
        
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function ubahProfil(Request $request){
        $user_token = $request->header('Authorization');

        if($user_token !== null){
            User::where('token', $user_token)->update([
                'nama' => htmlspecialchars(trim($request->nama)),
                'no_hp' => htmlspecialchars(trim($request->no_hp))
            ]);

            return response()->json(['message' => 'Profil berhasil diubah']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}