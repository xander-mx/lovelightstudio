<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payments extends Model
{
  protected $table = 'payments';
  protected $fillable = ['amount', 'voucher', 'payment_type', 'id_event'];

  public function event(): BelongsTo
  {
    return $this->belongsTo(Events::class, 'id_event','id');
  }
}