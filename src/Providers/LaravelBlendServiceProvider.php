<?php

namespace Tyk\LaravelBlend\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Tyk\LaravelBlend\Commands\AssetsDigest;
use Tyk\LaravelBlend\Commands\ClientWatch;
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
                AssetsDigest::class,
                ClientWatch::class,    
            ]);
        }
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        $this->publishes([
            __DIR__.'/../../public/js' => public_path('js'),
        ], 'blend');

        Blade::directive("blend", function () {
            $env = config('app.env');
            $assetsJsFile = config('blend.output.js_file') ?? public_path('js/app.js');
            $appUrl = config('app.url');

            if($env === 'production') {
                return "<script src='{$assetsJsFile}'></script>";
            }

            $scripts = [
                "<script src='https://js.pusher.com/7.0/pusher.min.js'></script>",
                "<script src='{$appUrl}/js/echo.js'></script>",
                "<script src='{$appUrl}/js/blend.js'></script>",
                "<script src='{$appUrl}/js/app.js'></script>"
            ];

            return implode("", $scripts);
        });
    }
}
