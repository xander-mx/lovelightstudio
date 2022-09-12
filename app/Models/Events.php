<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Events extends Model
{
  protected $table = 'events';
  protected $fillable = ['customer', 'id_center', 'center', 'id_service', 'service', 'price', 'discount', 'balance',
     'email', 'phone', 'personalize', 'status', 'coordinator'];

  public function payments(): HasMany
  {
    return $this->hasMany(Payments::class, 'id_event');
  }

  public function gifts(): HasMany
  {
    return $this->hasMany(Gifts::class, 'id_event');

  }
}