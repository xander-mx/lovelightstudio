<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Events extends Model
{
  protected $table = 'events';
  protected $fillable = ['customer', 'id_center', 'center', 'id_service', 'service', 'price', 'discount', 'balance',
     'email', 'phone', 'personalize'];

  public function payments(): HasMany
  {
    return $this->hasMany(Payments::class, 'id_event');
  }
}