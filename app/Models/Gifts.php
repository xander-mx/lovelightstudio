<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gifts extends Model
{
  protected $table = 'gifts';
  protected $fillable = array('id_event', 'type', 'photos');

  public function event(): BelongsTo
  {
    return $this->belongsTo(Events::class, 'id_event', 'id');
  }
}