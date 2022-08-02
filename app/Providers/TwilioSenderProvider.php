<?php

namespace App\Providers;

use App\Notifications\TwilioSender;
use Illuminate\Support\ServiceProvider;

class TwilioSenderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('twiliosender', function() {
          return new TwilioSender();
        });
    }
}
