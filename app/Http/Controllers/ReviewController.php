<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use stdClass;

class ReviewController extends Controller {

    public function review($id){
        //mengambil review toko
        $review = Review::select('review.id', 'users.nama', 'review.tanggal', 'review.komentar', 'review.rating', 'review.gambar')
                ->join('users', 'review.id_user', '=', 'users.id')
                ->where('review.id_toko', $id)
                ->orderBy('review.tanggal', 'DESC')
                ->get();

        //response api
        $response =  new stdClass();
        $response->tanggal = date('d-m-Y');
        $response->jumlah = $review->count();
        $response->review = $review;

        return response()->json($response);
    }

    public function addReview($id, Request $request){
        //get id_user
        $id_user = auth()->guard('api')->user()->id;

        if($id_user){
            Review::create([
                'id_user' => $id_user,
                'id_toko' => $id,
                'rating' => $request->rating,
                'komentar' => htmlspecialchars($request->komentar),
                'tanggal' => date('Y-m-d')
            ]);

            return response()->json(['message' => 'Anda Berhasil Menambahkan Review!']);
        }
        // jika akun belum ada
        else{
            return response()->json(['message' => 'User ID Tidak Didapatkan'], 400);
        }        
    }
}