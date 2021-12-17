<?php

namespace App\Models;

use App\Casts\RatingCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    use HasFactory;
    protected $table = 'review';
    protected $fillable = ['id_menu', 'id_jenis_promo', 'persentase', 'tanggal_mulai', 'durasi'];
    public $timestamps = false;
    protected $casts = [
        'rating' => RatingCast::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function toko(){
        return $this->belongsTo(Toko::class);
    }
    
}