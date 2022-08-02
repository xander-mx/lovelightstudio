<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    use HasFactory;
    protected $table = "reservations";
    protected $primaryKey = "idReservation";
    protected $fillable = ['pax', 'num_habitacion', 'idSchedule', 'idCenter', 'customer', 'phone', 'email'];

    public function center()
    {
        return $this->hasOne(Centers::class, 'idCenter', 'idCenter');
    }

    public function scopeFecha($query,$nombre)
    {
        # code..
        if($nombre != null && $nombre != ""){
            $separar = explode("-",$nombre);
            $fecha = $nombre." 00:00:00";
            return $query->where('date','like',"%$fecha%");

        }
    }

    public function scopeMes($query,$nombre)
    {
        # code..
        if($nombre != null && $nombre != ""){
          
            return $query->where('mes','like',"%$nombre%");

        }
    }

    public function client()
    {
        return $this->hasOne(User::class, 'idUser', 'idClient');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'idUser', 'idManager');
    }

    public function receptionist()
    {
        return $this->hasOne(User::class, 'idUser', 'idReceptionist');
    }
    public function receptioninfo()
    {
        return $this->hasOne(ReservaInfo::class, 'id_reserva', 'idReservation');
    }
    public function schedule()
    {
        return $this->hasOne(Schedules::class, 'idSchedule', 'idSchedule');
    }
}
