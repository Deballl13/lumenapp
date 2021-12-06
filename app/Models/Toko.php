<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model {

    use HasFactory;
    protected $table = 'toko';
    protected $fillable = ['gambar', 'nama', 'alamat', 'tipe', 'no_hp', 'ig', 'web', 'hari_ops', 'fasilitas'];
    protected $timestamps = false;

    public function menu(){
        return $this->hasMany(Menu::class);
    }

    public function review(){
        return $this->hasMany(Review::class);
    }

    public function metodePembayaranToko(){
        return $this->hasMany(MetodePembayaranToko::class);
    }
    
}