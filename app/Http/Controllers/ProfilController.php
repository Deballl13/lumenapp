<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller {
    
    public function ubahPassword(Request $request){
        $user_token = $request->header('Authorization');

        if($user_token !== null){
            $user = User::where('token', $user_token)->first();
            $user->password = Hash::make($request->password);
            $user->save(); 

            return response()->json(['message' => 'Ubah password berhasil']);
        }
        return response()->json(['message' => 'Ubah password gagal'], 401);
    }

    public function ubahProfil(Request $request){
        $user_token = $request->header('Authorization');

        if($user_token !== null){
            $user = User::where('token', $user_token)->first();
            $user->nama = trim($request->nama);
            $user->no_hp = trim($request->no_hp);
            $user->save(); 

            return response()->json(['message' => 'Ubah profil berhasil']);
        }
        return response()->json(['message' => 'Ubah profil gagal'], 401);
    }
}