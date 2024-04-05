<?php

namespace App\Providers;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;
use App\Services\PSR\Http\Client\Adapter\Guzzle as PsrHttpClientGuzzleAdapter;

class HttpClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [ClientInterface::class];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(
            ClientInterface::class,
            static function () {
                return new PsrHttpClientGuzzleAdapter(
                    new GuzzleClient(
                        [
                            'timeout'         => 5,
                            'connect_timeout' => 5,
                        ]
                    )
                );
            }
        );
    }
}
