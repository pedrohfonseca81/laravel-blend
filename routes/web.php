<?php

use Illuminate\Support\Facades\Route;
use Tyk\LaravelBlend\Controllers\BlendClientController;

Route::get("blend/client", [BlendClientController::class, 'index']);