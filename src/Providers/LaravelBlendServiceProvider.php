<?php

namespace Tyk\LaravelBlend\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Tyk\LaravelBlend\Commands\AssetsDigest;
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

        if($this->app->runningInConsole()) {
            $this->commands([
                AssetsDigest::class
            ]);
        }
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        Blade::directive("blend", function () {
            return "<script>...</script>";
        });
    }
}
