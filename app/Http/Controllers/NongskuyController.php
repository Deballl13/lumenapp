<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Review;
use App\Models\Toko;
use Illuminate\Http\Request;
use stdClass;

class NongskuyController extends Controller {
    
    public function index(){
        // mengambil list toko
        $toko = Toko::select('id', 'gambar', 'nama_toko', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas',
                        Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                    ->get();

        // build api response
        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $toko->count();
        $response->toko = $toko; 

        return response()->json($response);
    }

    public function populer(){
        (double) $latitude = request()->query('latitude');
        (double) $longitude = request()->query('longitude');
        
        // mengambil list toko dari paling populer
        $populer = Review::select('toko.id', 'toko.gambar', 'toko.nama_toko', 'toko.alamat', 
                                'toko.tipe', Review::raw('avg(review.rating) as rating'), Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                        ->rightJoin('toko', 'review.id_toko', '=', 'toko.id')
                        ->groupBy('toko.id')
                        ->orderByRaw('rating DESC NULLS LAST')
                        ->get();

        // build api response
        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $populer->count();
        $response->toko_populer = $populer; 

        return response()->json($response);
    }

    public function show($id){
        // mengambil detail toko
        $toko = Toko::select('id', 'gambar', 'nama_toko', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas',
                        Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                    ->whereId($id)
                    ->get();

        return response()->json(['tanggal' => date('d-m-Y'), 'toko' => $toko]);
    }

    public function search(Request $request){
        // ambil keyword
        $keyword = htmlspecialchars(trim($request->keyword));
        
        // cari data berdasarkan keyword dan case insensitive
        $toko = Toko::select('id', 'gambar', 'nama_toko', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas',
                        Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'))
                    ->where('nama_toko', 'ILIKE', "%{$keyword}%")
                    ->get();
        
        // build api response
        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $toko->count();
        $response->search_result = $toko; 

        return response()->json($response);
    }

    public function menu($id){

        //cari data menu berdasarkan id toko
        $menu = Menu::select('id', 'nama_menu', 'harga', 'gambar', 'status')
                ->orderBy('status', 'DESC')
                ->where('id_toko', $id)
                ->get();

        // build api response
        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $menu->count();
        $response->menu = $menu; 

        return response()->json($response);
    }


}