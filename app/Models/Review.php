<?php

namespace App\Models;

use App\Casts\IntegerCast;
use App\Casts\DateCast;
use App\Casts\DoubleCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    use HasFactory;
    protected $table = 'review';
    protected $fillable = ['id_user', 'id_toko', 'komentar', 'tanggal', 'rating', 'gambar'];
    public $timestamps = false;
    protected $casts = [
        'id' => IntegerCast::class,
        'id_user' => IntegerCast::class,
        'id_toko' => IntegerCast::class,
        'tanggal' => DateCast::class,
        'rating' => DoubleCast::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function toko(){
        return $this->belongsTo(Toko::class);
    }
    
}