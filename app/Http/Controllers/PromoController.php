<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Toko;
use stdClass;

class PromoController extends Controller {
    
    public function index(Request $request){
        $user_token = $request->header('Authorization');
        
        if($user_token !== null){
            $toko = Toko::select('toko.id as id_toko', 'nama_toko')
                            ->join('menu', 'toko.id', '=', 'menu.id_toko')
                            ->join('promo', 'menu.id', '=', 'promo.id_menu')
                            ->whereDate('promo.tanggal_mulai', '<=', date('Y-m-d'))
                            ->whereRaw("promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?", [date('Y-m-d')])
                            ->distinct()
                            ->get();            

            foreach($toko as $t):                
                $t->menu = Menu::select('menu.id as id_menu', 'menu.nama_menu', 'menu.harga', 'menu.gambar', 'promo.persentase', 'jenis_promo.nama_jenis_promo')
                            ->join('promo', 'menu.id', '=', 'promo.id_menu')
                            ->join('jenis_promo', 'promo.id_jenis_promo', '=', 'jenis_promo.id')
                            ->where('menu.id_toko', $t->id_toko)
                            ->whereDate('promo.tanggal_mulai', '<=', date('Y-m-d'))
                            ->whereRaw("promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?", [date('Y-m-d')])
                            ->get();
            endforeach;

            $response = new stdClass();
            $response->tanggal = date('d-m-Y');
            $response->promo = $toko;
            return response()->json($response);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

}