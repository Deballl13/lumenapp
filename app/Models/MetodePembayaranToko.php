<?php

namespace App\Models;

use App\Casts\IntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaranToko extends Model {

    use HasFactory;
    protected $table = 'metode_pembayaran_toko';
    protected $fillable = ['id_toko', 'id_metode_bayar', 'no_rek'];
    public $timestamps = false;
    protected $casts = [
        'id' => IntegerCast::class,
        'id_metode_bayar' => IntegerCast::class,
        'id_toko' => IntegerCast::class,
    ];

    public function metodePembayaran(){
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function toko(){
        return $this->belongsTo(Toko::class);
    }

    public function pemesanan(){
        return $this->hasMany(Pemesanan::class);
    }
    
}