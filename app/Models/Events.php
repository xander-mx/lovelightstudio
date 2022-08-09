<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
  protected $table = 'events';
  protected $fillable = ['customer', 'id_center', 'center', 'id_service', 'service', 'price', 'discount', 'balance',
    'photographer', 'phone', 'personalize'];
}