<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Facade;

class TwilioSenderFacade extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'twiliosender';
  }
}