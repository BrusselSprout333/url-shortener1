<?php

namespace App\Providers;

use App\Services\SafeBrowsing\Client;
use Illuminate\Support\ServiceProvider;

class SafeBrowsingApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(Client::class)
            ->needs('$apiKey')
            ->giveConfig('google.safeBrowsing.apiKey');

        $this->app
            ->when(Client::class)
            ->needs('$clientId')
            ->giveConfig('google.safeBrowsing.clientId');
    }
}
