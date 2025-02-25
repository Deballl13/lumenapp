<?php

namespace App\Models;

use App\Casts\IntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model {

    use HasFactory;
    protected $table = 'metode_pembayaran';
    protected $fillable = 'nama_metode_pembayaran';
    public $timestamps = false;
    protected $casts = [
        'id' => IntegerCast::class,
    ];

    public function metodePembayaranToko(){
        return $this->hasMany(MetodePembayaranToko::class);
    }
    
}