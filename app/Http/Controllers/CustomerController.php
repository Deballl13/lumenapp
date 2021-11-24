<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller {

    public function register (Request $request) {
        $customer = new Customer();
        $customer->nama = trim($request->nama);
        $customer->email = trim($request->email);
        $customer->no_hp = trim($request->no_hp);
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json(['message' => 'Pendaftaran berhasil']);
    }
    
}