<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Facade;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Broadcast::routes();
        // require base_path('routes/channels.php');
    }
}