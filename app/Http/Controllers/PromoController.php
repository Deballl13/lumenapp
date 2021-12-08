<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class PromoController extends Controller {
    
    public function index(Request $request){
        $user_token = $request->header('Authorization');
        
        if($user_token !== null){
            $time = new Carbon();
            $toko = Toko::select('toko.id as id_toko', 'nama_toko')
                            ->join('menu', 'toko.id', '=', 'menu.id_toko')
                            ->join('promo', 'menu.id', '=', 'promo.id_menu')
                            ->whereDate('promo.tanggal_mulai', '<=', $time->now())
                            ->whereRaw("promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?", [$time->now()->format('Y-m-d')])
                            ->distinct()
                            ->get();

            $response = new stdClass();
            $response->tanggal = date('d-m-Y');
            $response->jumlah = $toko->count();            
    
            foreach($toko as $t):
                $t->menu = new stdClass();
                
                $menu = Menu::select('menu.id as id_menu', 'menu.nama_menu', 'menu.harga', 'menu.gambar', 'menu.status','promo.persentase', 'jenis_promo.nama_jenis_promo')
                            ->join('promo', 'menu.id', '=', 'promo.id_menu')
                            ->join('jenis_promo', 'promo.id_jenis_promo', '=', 'jenis_promo.id')
                            ->where('menu.id_toko', $t->id_toko)
                            ->whereDate('promo.tanggal_mulai', '<=', $time->now())
                            ->whereRaw("promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?", [$time->now()->format('Y-m-d')])
                            ->get();

                $t->menu = $menu;
            endforeach;

            $response->promo = $toko;
            return response()->json($response);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

}