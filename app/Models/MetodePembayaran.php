<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model {

    use HasFactory;
    protected $table = 'metode_pembayaran';
    protected $fillable = 'nama';
    public $timestamps = false;

    public function metodePembayaranToko(){
        return $this->hasMany(MetodePembayaranToko::class);
    }
    
}