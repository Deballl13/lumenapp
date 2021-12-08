<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPromo extends Model {

    use HasFactory;
    protected $table = 'jenis_promo';
    protected $fillable = 'nama';
    public $timestamps = false;

    public function promo(){
        return $this->hasMany(JenisPromo::class);
    }
    
}