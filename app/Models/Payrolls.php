<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payrolls extends Model
{
  protected $table = 'payroll';
  protected $fillable = ['concept', 'total', 'initDate', 'closeDate'];

  public function commissions(): HasMany
  {
    return $this->hasMany(Commissions::class, 'id_payroll',  'id');
  }
}