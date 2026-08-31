<?php

namespace App\Providers;

use App\Integrations\Sms\LogSmsGateway;
use App\Integrations\Sms\SmsGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsGateway::class, LogSmsGateway::class);
    }

    public function boot(): void
    {
        //
    }
}
