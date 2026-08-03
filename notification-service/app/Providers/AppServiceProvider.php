<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Mail::extend('mailtrap', function () {
            $factory = new \Symfony\Component\Mailer\Bridge\Mailtrap\Transport\MailtrapTransportFactory();

            $inboxId = config('services.mailtrap.default_inbox');
            $options = [];
            if ($inboxId) {
                $options['inboxId'] = $inboxId;
            }

            $dsn = new \Symfony\Component\Mailer\Transport\Dsn(
                'mailtrap+sandbox',
                'default',
                config('services.mailtrap.secret'),
                null,
                null,
                $options
            );

            return $factory->create($dsn);
        });
    }
}
