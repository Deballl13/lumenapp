<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model {

    use HasFactory;
    protected $table = 'menu';
    protected $fillable = ['id_toko', 'nama', 'harga', 'gambar', 'status'];
    protected $timestamps = false;

    public function toko(){
        return $this->belongsTo(Toko::class);
    }

    public function promo(){
        return $this->hasMany(Promo::class);
    }
    
}