<?php

namespace App\Models;

use App\Casts\DateCast;
use App\Casts\TimeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model {

    use HasFactory;
    protected $table = 'pemesanan';
    protected $fillable = ['id_user', 'id_detail_metode_bayar_toko', 'tanggal', 'waktu', 'jumlah_kursi', 'dp', 'status'];
    public $timestamps = false; 
    protected $casts = [
        'tanggal' => DateCast::class,
        'waktu' => TimeCast::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function metodePembayaranToko(){
        return $this->belongsTo(MetodePembayaranToko::class);
    }
    
}