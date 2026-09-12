<?php

namespace App\Providers;

use App\Mail\Transport\GmailApiTransport;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        Mail::extend('gmail-api', function (array $config) {
            return new GmailApiTransport(
                $config['client_id'],
                $config['client_secret'],
                $config['refresh_token'],
            );
        });
    }
}
