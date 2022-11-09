<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{
  protected $table = 'configurations';
  protected $fillable = ['name', 'value', 'state'];

  public function closeDate()
  {
    return Configurations::where('name', '=', 'fecha_cierre')->where('state', '=', 1)->first();
  }
}