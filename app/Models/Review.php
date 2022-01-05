<?php

namespace App\Models;

use App\Casts\RatingCast;
use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    use HasFactory;
    protected $table = 'review';
    protected $fillable = ['id_user', 'id_toko', 'komentar', 'tanggal', 'rating', 'gambar'];
    public $timestamps = false;
    protected $casts = [
        'tanggal' => DateCast::class,
        'rating' => RatingCast::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function toko(){
        return $this->belongsTo(Toko::class);
    }
    
}