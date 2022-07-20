<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comisiones extends Model
{
    use HasFactory;
    protected $table = "comisiones";
    protected $primaryKey = "id";

    public function scopeMes($query,$nombre)
    {
        # code..
        if($nombre != null && $nombre != ""){
            return $query->where('mes','like',"%$nombre%");

        }
    }

    
    public function scopeAno($query,$nombre)
    {
        # code..
        if($nombre != null && $nombre != ""){
            return $query->where('ano','like',"%$nombre%");

        }
    }
    public function scopeTrabajador($query,$nombre)
    {
        # code..
        if($nombre != null && $nombre != ""){
            return $query->where('id_trabajador','like',"%$nombre%");

        }
    }
    public function trabajador()
    {
        return $this->hasOne(User::class, 'idUser', 'id_trabajador');
    }
}
