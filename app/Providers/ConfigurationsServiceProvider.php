<?php

namespace App\Providers;

use App\Models\Configurations;
use Illuminate\Support\ServiceProvider;

class ConfigurationsServiceProvider extends ServiceProvider
{
  public function register()
  {
  }

  public function boot()
  {
    $this->app->bind('configurations', function() {
      return new Configurations();
    });
  }
}