<?php

namespace Tyk\LaravelBlend\Facade;

use Illuminate\Support\Facades\Facade;

class NpmRegistry extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'npmregistry';
    }
}
