<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Toko;
use App\Models\MetodePembayaranToko;
use App\Models\MetodePembayaran;
use App\Models\User;
use stdClass;


class PesanController extends Controller {
    //
    public function riwayat(Request $request){
        $user_token = $request->header('Authorization');

        if($user_token !== null){
            $user = User::select('id')
                        ->where('token', $user_token)
                        ->first();
            
            $toko = Pemesanan::select('toko.id as id_toko', 'toko.nama_toko', 'toko.gambar as gambar_toko',
                                        'pemesanan.no_faktur', 'pemesanan.tanggal', 'pemesanan.waktu',
                                        'pemesanan.jumlah_kursi', 'pemesanan.dp', 
                                        'metode_pembayaran.nama_metode_pembayaran as metode_bayar', 'pemesanan.status')
                        ->join('metode_pembayaran_toko', 'pemesanan.id_metode_bayar_toko', '=', 'metode_pembayaran_toko.id')
                        ->join('metode_pembayaran', 'metode_pembayaran.id', '=', 'metode_pembayaran_toko.id_metode_bayar')
                        ->join('toko', 'metode_pembayaran_toko.id_toko', '=', 'toko.id')
                        ->where('pemesanan.id_user', $user->id)
                        ->get();

            $response =  new stdClass();
            $response->tanggal = date('d-m-Y');
            $response->riwayat = [];

            for($i=0; $i<$toko->count(); $i++):
                
                if(($i+1 === $toko->count()) || ($toko[$i]->id_toko === $toko[$i+1]->id_toko)){
                    $riwayat_toko =  new stdClass();

                    $riwayat_toko->id_toko = $toko[$i]->id_toko;
                    $riwayat_toko->nama_toko = $toko[$i]->nama_toko;
                    $riwayat_toko->gambar_toko = $toko[$i]->gambar_toko;
                    
                    array_push($response->riwayat, $riwayat_toko);


                }


                // else if($toko[$i]->id_toko !== $toko[$i+1]->id_toko){
                //     $riwayat_toko =  new stdClass();

                //     $riwayat_toko->id_toko = $toko[$i]->id_toko;
                //     $riwayat_toko->nama_toko = $toko[$i]->nama_toko;
                //     $riwayat_toko->gambar_toko = $toko[$i]->gambar_toko;
                    
                //     array_push($response->riwayat, $riwayat_toko);
                // }
                
                
            endfor;
            // foreach($toko as $t):                
            //     $t->pemesanan =  
            // endforeach;

            return response()->json($response);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}