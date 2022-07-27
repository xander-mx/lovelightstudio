<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaInfo extends Model
{
    use HasFactory;
    protected  $table = "reservations_info_finish";
    protected $primaryKey = "id";
    protected $fillable = ['email', 'email2', 'micelaneo', 'telefono1', 'telefono2', 'canva', 'direccion'];
}
