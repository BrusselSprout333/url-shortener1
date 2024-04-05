<?php

namespace App\Providers;

use App\Repositories\ShortLinkRepository;
use App\Services\Link\Converter;
use App\Services\Link\DecoderInterface;
use App\Services\Link\EncoderInterface;
use App\Services\Link\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ShortLinkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RepositoryInterface::class, ShortLinkRepository::class);

        $this->app->singleton(Converter::class);
        $this->app
            ->when(Converter::class)
            ->needs('$baseUrl')
            ->giveConfig('shortener.baseUrl');

        $this->app->singleton(EncoderInterface::class, Converter::class);
        $this->app->singleton(DecoderInterface::class, Converter::class);
    }
}
