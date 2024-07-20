<?php

namespace Tyk\LaravelBlend\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Tyk\LaravelBlend\NpmRegistry;

class LaravelBlendServiceProvider extends ServiceProvider
{
    private $package = "@esbuild";

    private $baseUrl = "https://registry.npmjs.org";

    public function register(): void
    {
        $baseUrl = $this->baseUrl;
        $package = $this->package;

        Http::macro('esbuild', function () use ($package, $baseUrl) {
            return Http::baseUrl("{$baseUrl}/{$package}");
        });

        $this->app->bind('npmregistry', function () {
            return new NpmRegistry;
        });
    }

    public function boot(): void
    {
    }
}
