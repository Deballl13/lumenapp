<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use stdClass;

class PromoController extends Controller {
    
    public function index(){
        // mengambil data toko dan promo
        $promo = Toko::select('toko.id as id_toko', 'nama_toko', 'toko.gambar as gambar_toko', 'toko.alamat as alamat_toko',
                            Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'),
                            'menu.id as id_menu', 'menu.nama_menu', 'menu.harga', 'menu.gambar as gambar_menu', 'jenis_promo.nama_jenis_promo as jenis_promo', 'promo.persentase')
                        ->join('menu', 'toko.id', '=', 'menu.id_toko')
                        ->join('promo', 'menu.id', '=', 'promo.id_menu')
                        ->join('jenis_promo', 'promo.id_jenis_promo', '=', 'jenis_promo.id')
                        ->whereDate('promo.tanggal_mulai', '<=', date('Y-m-d'))
                        ->whereRaw("promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?", [date('Y-m-d')])
                        ->orderBy('promo.tanggal_mulai', 'desc')
                        ->get();

        // konfigurasi response api
        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $promo->count();
        $response->promo = $promo;

        return response()->json($response);
    }

}