<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Toko;
use stdClass;

class TokoController extends Controller {
    
    public function index(){
        $toko = Toko::select('id', 'gambar', 'nama_toko', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas',
                        Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                    ->get();

        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $toko->count();
        $response->toko = $toko; 

        return response()->json($response);
    }

    public function populer(){
        $populer = Review::select('toko.id', 'toko.gambar', 'toko.nama_toko', 'toko.alamat', 
                                'toko.tipe', Review::raw('avg(review.rating) as rating'), Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                        ->rightJoin('toko', 'review.id_toko', '=', 'toko.id')
                        ->groupBy('toko.id')
                        ->orderByRaw('rating DESC NULLS LAST')
                        ->get();

        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $populer->count();
        $response->toko_populer = $populer; 

        return response()->json($response);
    }

    public function show($id){
        $toko = Toko::select('id', 'gambar', 'nama_toko', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas',
                        Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                    ->whereId($id)
                    ->get();

        return response()->json(['tanggal' => date('d-m-Y'), 'toko' => $toko]);
    }

}