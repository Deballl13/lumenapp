<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User;
use stdClass;


class PesanController extends Controller {
    //
    public function riwayat(Request $request){
        $user_token = $request->header('Authorization');

        if($user_token !== null){
            // mengambil id user
            $user = User::select('id')
                        ->where('token', $user_token)
                        ->first();
            
            // mengambil data toko dan riwayat pemesanan
            $toko = Pemesanan::select('toko.id as id_toko', 'toko.nama_toko', 'toko.gambar as gambar_toko',
                                        'pemesanan.no_faktur', 'pemesanan.tanggal', 'pemesanan.waktu',
                                        'pemesanan.jumlah_kursi', 'pemesanan.dp', 
                                        'metode_pembayaran.nama_metode_pembayaran as metode_bayar', 'pemesanan.status')
                                ->join('metode_pembayaran_toko', 'pemesanan.id_metode_bayar_toko', '=', 
                                        'metode_pembayaran_toko.id')
                                ->join('metode_pembayaran', 'metode_pembayaran.id', '=', 
                                        'metode_pembayaran_toko.id_metode_bayar')
                                ->join('toko', 'metode_pembayaran_toko.id_toko', '=', 'toko.id')
                                ->where('pemesanan.id_user', $user->id)
                                ->get();

            // konfigurasi response api
            $response =  new stdClass();
            $response->tanggal = date('d-m-Y');

            // mengelompokkan data lalu mapping data
            $response->riwayat = collect($toko)->groupBy('id_toko')->map(function($data){
                $coll = collect($data);

                // mengambil data pertama setiap kelompok
                $coll_toko = collect($coll[0])->only(['id_toko', 'nama_toko', 'gambar_toko', 'pemesanan']);
                $coll_toko->put('pemesanan',

                    // mapping data pemesanan
                    $coll->map(function($data){
                        return collect($data)->only(['no_faktur', 'tanggal', 'waktu', 'jumlah_kursi', 'dp', 'metode_bayar', 'status'])->toArray();
                    })
                );

                return $coll_toko;
            })->values();

            return response()->json($response);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}