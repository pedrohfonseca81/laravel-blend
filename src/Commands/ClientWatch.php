<?php

namespace Tyk\LaravelBlend\Commands;

use Illuminate\Console\Command;
use Tyk\LaravelBlend\Events\FileChangedEvent;

class ClientWatch extends Command
{
    protected $signature = "client:watch (file)";

    protected $description = "Watch client assets";

    public function handle(string $file = "resources/js/app.js")
    {
        $this->info("Watching client assets for changes...");

        $directories = ['resources'];

        $absoluteDirectories = array_map(function ($dir) {
            return base_path($dir);
        }, $directories);

        $command = array_merge(
            ['inotifywait', '-m', '-r', '--format', '%w%f', '--event', 'modify', '--event', 'close_write'],
            $absoluteDirectories
        );

        $process = new \Symfony\Component\Process\Process($command);
        $process->setTimeout(null);
        $process->start();

        foreach ($process as $line) {
            event(new FileChangedEvent);
            $this->call('assets:digest', ['--file' => $file, '--show_message' => false]);
        }
    }
}
