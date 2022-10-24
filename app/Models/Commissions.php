<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commissions extends Model
{
  protected $table = 'commissions';
  protected $fillable = ['concept', 'status', 'authorized_by', 'employee', 'amount', 'date'];

  public function employee(): HasOne
  {
    return $this->hasOne(User::class, 'employee');
  }

  public function authorizedBy(): HasOne
  {
    return $this->hasOne(User::class, 'authorized_by');
  }

  public function payroll(): BelongsTo
  {
    return $this->belongsTo(Payrolls::class, 'id_payroll', 'id');
  }

}