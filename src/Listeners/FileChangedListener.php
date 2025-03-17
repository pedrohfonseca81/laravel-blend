<?php

namespace App\Listeners;

use Tyk\LaravelBlend\Events\FileChangedEvent;

class FileChangedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FileChangedEvent $event): void {}
}
