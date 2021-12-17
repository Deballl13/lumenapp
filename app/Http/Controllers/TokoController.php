<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Toko;
use stdClass;

class TokoController extends Controller {
    
    public function index(){
        $populer = Toko::all();

        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $populer->count();
        $response->populer = $populer; 

        return response()->json($response);
    }

    public function populer(){
        $populer = Review::select('toko.id', 'toko.gambar', 'toko.nama_toko', 'toko.alamat', 
                                'toko.tipe', Review::raw('avg(review.rating) as rating'))
                        ->rightJoin('toko', 'review.id_toko', '=', 'toko.id')
                        ->groupBy('toko.id')
                        ->orderByRaw('rating DESC NULLS LAST')
                        ->get();

        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $populer->count();
        $response->populer = $populer; 

        return response()->json($response);
    }

    public function show($id){
        $toko = Toko::find($id);

        return response()->json(['tanggal' => date('d-m-Y'), 'toko' => $toko]);
    }

}