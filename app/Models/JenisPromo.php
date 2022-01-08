<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPromo extends Model {

    use HasFactory;
    protected $table = 'jenis_promo';
    protected $fillable = 'nama_jenis_promo';
    public $timestamps = false;
    protected $casts = [
        'id' => IntegerCast::class,
    ];

    public function promo(){
        return $this->hasMany(JenisPromo::class);
    }
    
}