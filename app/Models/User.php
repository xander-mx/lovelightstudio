<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

  protected $table = "users";
  protected $primaryKey = 'idUser';
  protected $fillable = ['name', 'email', 'phone', 'password'];
  protected $hidden = ['password', 'remember_token'];
  protected $casts = ['email_verified_at' => 'datetime'];

    public function getPerfil(): HasOne
    {
        return $this->hasOne(ParameterValues::class, 'idValueParameter', 'idPerfil');
    }
}
