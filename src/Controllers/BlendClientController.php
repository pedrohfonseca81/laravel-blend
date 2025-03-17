<?php

namespace Tyk\LaravelBlend\Controllers;

use App\Http\Controllers\Controller;

class BlendClientController extends Controller
{
    public function index()
    {
        $jsFile = public_path('js/app.js');

        $content = file_get_contents($jsFile);

        return response($content, 200)
            ->header('Content-Type', 'application/javascript');
    }
}
