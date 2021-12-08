<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaranToko extends Model {

    use HasFactory;
    protected $table = 'metode_pembayaran_toko';
    protected $fillable = ['id_toko', 'id_metode_bayar'];
    public $timestamps = false;

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