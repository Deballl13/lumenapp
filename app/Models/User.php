<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use App\Casts\IntegerCast;

class User extends Model implements AuthenticatableContract, AuthorizableContract {

    use Authenticatable, Authorizable, HasFactory;
    
    protected $fillable = ['nama', 'email', 'no_hp', 'password', 'token'];
    public $timestamps = false;
    protected $hidden = ['id', 'password'];
    protected $casts = [
        'id' => IntegerCast::class
    ];

    public function review(){
        return $this->hasMany(Review::class);
    }
    
}