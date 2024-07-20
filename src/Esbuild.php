<?php

namespace Tyk\LaravelBlend;

use PharData;
use Tyk\LaravelBlend\Trait\WithKernelPlatform;

class Esbuild
{
    use WithKernelPlatform;

    public function extractTarball(string $path)
    {
        $fromDist = storage_path("app/$path");
        $targetDist = storage_path("app/dist_$path/");

        $phar = new PharData($fromDist);

        $phar->extractTo($targetDist);

        $binPath = base_path('vendor/bin/esbuild');

        copy("{$targetDist}/package/bin/esbuild", $binPath);
        chmod($binPath, 755);

        unlink($fromDist);
        rmdir($targetDist);
    }
}
