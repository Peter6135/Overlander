<?php

namespace App\Providers;

use App\Mail\Transport\GmailApiTransport;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
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

        // Visitors have no stored language, so the reset email is written in both.
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
            $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject(__('auth.reset_email_subject', [], 'en') . ' / ' . __('auth.reset_email_subject', [], 'id'))
                ->line('We received a request to reset your Overlander password. Use the button below to choose a new one.')
                ->line('Kami menerima permintaan reset password Overlander kamu. Pakai tombol di bawah buat bikin password baru.')
                ->action('Reset password', $url)
                ->line("This link expires in {$minutes} minutes. If you did not ask for this, you can ignore this email.")
                ->line("Link ini kedaluwarsa dalam {$minutes} menit. Kalau bukan kamu yang meminta, abaikan saja email ini.");
        });
    }
}
