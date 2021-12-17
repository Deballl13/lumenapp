<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use stdClass;


class PesanController extends Controller {
    
    public function riwayat(){
        // mengambil data toko dan riwayat pemesanan
        $riwayat = Pemesanan::select('pemesanan.no_faktur', 'toko.id as id_toko', 'toko.nama_toko', 
                                    'toko.gambar as gambar_toko', 'pemesanan.tanggal', 'pemesanan.waktu',
                                    'pemesanan.jumlah_kursi', 'pemesanan.dp', 
                                    'metode_pembayaran.nama_metode_pembayaran as metode_bayar', 'pemesanan.status')
                            ->join('metode_pembayaran_toko', 'pemesanan.id_metode_bayar_toko', '=', 
                                    'metode_pembayaran_toko.id')
                            ->join('metode_pembayaran', 'metode_pembayaran.id', '=', 
                                    'metode_pembayaran_toko.id_metode_bayar')
                            ->join('toko', 'metode_pembayaran_toko.id_toko', '=', 'toko.id')
                            ->whereIdUser(auth()->guard('api')->user()->id)
                            ->orderBy('pemesanan.no_faktur', 'desc')
                            ->get();

        // konfigurasi response api
        $response =  new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $riwayat->count();
        $response->riwayat = $riwayat;

        return response()->json($response);
    }
}