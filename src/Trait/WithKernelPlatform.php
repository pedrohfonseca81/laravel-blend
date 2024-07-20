<?php

namespace Tyk\LaravelBlend\Trait;

use Error;
use Illuminate\Support\Stringable;

trait WithKernelPlatform
{
    private function getCpuArch(): Stringable
    {
        return str(php_uname('m'));
    }

    private function getOsName(): Stringable
    {
        return str(php_uname('s'));
    }

    public function getUserPlatform(): Stringable
    {
        $os = $this->getOsName();
        $arch = $this->getCpuArch();

        $osName = match (true) {
            $os == "Linux" => "linux",
            $os->startsWith("Win") => "win32"
        };

        $cpuArch = match (true) {
            $arch == "AMD64" => "x64",
            $arch == "x86_64" => "x64",
            default => throw new Error("Esbuild is not available for this platform.")
        };

        return str("{$osName}-{$cpuArch}");
    }
}
