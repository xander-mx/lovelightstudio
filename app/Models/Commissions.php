<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commissions extends Model
{
  protected $table = 'commissions';
  protected $fillable = ['concept', 'status', 'authorized_by', 'employee', 'amount', 'date', 'reservation'];

  public function employeeData(): HasOne
  {
    return $this->hasOne(User::class, 'idUser', 'employee');
  }

  public function authorizedBy(): HasOne
  {
    return $this->hasOne(User::class, 'idUser','authorized_by');
  }

  public function payroll(): BelongsTo
  {
    return $this->belongsTo(Payrolls::class, 'id_payroll', 'id');
  }

}