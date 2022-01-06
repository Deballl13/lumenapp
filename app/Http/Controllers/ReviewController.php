<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
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
}