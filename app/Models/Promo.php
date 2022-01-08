<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model {

    use HasFactory;
    protected $table = 'promo';
    protected $fillable = ['id_menu', 'id_jenis_promo', 'persentase', 'tanggal_mulai', 'durasi'];
    public $timestamps = false;
    protected $casts = [
        'id' => IntegerCast::class,
        'id_menu' => IntegerCast::class,
        'id_jenis_promo' => IntegerCast::class,
        'tanggal_mulai' => DateCast::class,
    ];

    public function jenisPromo(){
        return $this->belongsTo(JenisPromo::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
    
}