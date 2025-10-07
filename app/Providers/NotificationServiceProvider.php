<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->when(\App\Channels\WhatsAppChannel::class)
            ->give(function () {
                return new \App\Channels\WhatsAppChannel();
            });

        // Register the channel with the framework
        Notification::extend('whatsapp', function ($app) {
            return $app->make(\App\Channels\WhatsAppChannel::class);
        });
    }
}
