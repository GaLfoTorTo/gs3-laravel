<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Perfil;

class Usuario extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    
    protected $table = 'usuarios';
    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function perfil(){
        return $this->belongsTo(Perfil::class, 'perfil_id','id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
