<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JenisPromo;
use App\Models\Menu;
use App\Models\Review;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;
use stdClass;

class NongskuyController extends Controller {
    
    public function terdekat(){
        (double) $latitude = request()->query('latitude');
        (double) $longitude = request()->query('longitude');
        
        // mengambil list toko terdekat
        $toko = Toko::select('id', 'gambar', 'nama_toko', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas',
                        Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'), 
                        Toko::raw('cast(6371 * acos(cos( radians(ST_Y(lokasi::geometry))) 
                                * cos( radians(?)) * cos( radians(?) - radians(ST_X(lokasi::geometry))) 
                                + sin(radians(ST_Y(lokasi::geometry))) 
                                * sin(radians(?))) as decimal(2,1)) as jarak'))
                    ->whereRaw('6371 * acos(cos( radians(ST_Y(lokasi::geometry))) 
                                * cos( radians(?)) * cos( radians(?) - radians(ST_X(lokasi::geometry))) 
                                + sin(radians(ST_Y(lokasi::geometry))) 
                                * sin(radians(?))) <= 5.0')
                    ->setBindings([$latitude, $longitude, $latitude, $latitude, $longitude, $latitude])
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
                                'toko.tipe', Review::raw('avg(review.rating) as rating'), Toko::raw('ST_Y(lokasi::geometry) as latitude'), Toko::raw('ST_X(lokasi::geometry) as longitude'),
                                Toko::raw('cast(6371 * acos(cos( radians(ST_Y(lokasi::geometry))) 
                                * cos( radians(?)) * cos( radians(?) - radians(ST_X(lokasi::geometry))) 
                                + sin(radians(ST_Y(lokasi::geometry))) 
                                * sin(radians(?))) as decimal(2,1)) as jarak'))
                        ->rightJoin('toko', 'review.id_toko', '=', 'toko.id')
                        ->groupBy('toko.id')
                        ->orderByRaw('rating DESC NULLS LAST')
                        ->setBindings([$latitude, $longitude, $latitude])
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
        // cek status guest
        $guest = (request()->query('guest') !== null) ? boolval(request()->query('guest')) : false;
        $menu = null;

        // jika guest adalah true
        if($guest){
            //cari data menu berdasarkan id toko
            $menu = Menu::select('menu.id', 'menu.nama_menu', 'menu.harga', 'menu.gambar')
                        ->where('menu.id_toko', $id)
                        ->orderBy('status', 'DESC')
                        ->get();
        }

        // jika bukan guest
        else{
            //cari data menu berdasarkan id toko
            $menu = Menu::select('menu.id', 'menu.nama_menu', 'menu.harga', 'menu.gambar', 'menu.status', 
                            DB::raw("CASE (promo.tanggal_mulai <= ?) AND (promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?) WHEN true THEN nama_jenis_promo END as jenis_promo"),
                            DB::raw("CASE (promo.tanggal_mulai <= ?) AND (promo.tanggal_mulai + (promo.durasi-1)*INTERVAL '1 day' >= ?) WHEN true THEN promo.persentase END as persentase"))
                        ->leftJoin('promo', 'menu.id', '=', 'promo.id_menu')
                        ->leftJoin('jenis_promo', 'promo.id_jenis_promo', '=', 'jenis_promo.id')
                        ->where('menu.id_toko', '?')
                        ->orderBy('status', 'DESC')
                        ->setBindings([date('Y-m-d'), date('Y-m-d'), date('Y-m-d'), date('Y-m-d'), $id])
                        ->get();
        }
            
        // build api response
        $response = new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $menu->count();
        $response->menu = $menu; 

        return response()->json($response);
    }


}