<?php

namespace Tyk\LaravelBlend\Commands;

use DateTime;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsDigest extends Command
{
    protected $signature = "assets:digest {--file=resources/js/app.js} {--show_message=true}";

    protected $description = "Digest assets to public folder";

    public function handle()
    {
        $file = $this->option('file');
        $showMessage = $this->option('show_message');

        $beginMs = round(microtime(true) * 1000);

        $esbuildBin = base_path('vendor/bin/esbuild');
        $outFile = public_path('js/app.js');

        $process = new Process([$esbuildBin, "--minify", $file, "--bundle", "--outfile=$outFile", "--analyze"]);

        $process->setTimeout(null);
        $process->run();
        $process->wait();

        $endMs = round(microtime(true) * 1000) - $beginMs;

        if ($showMessage) {
            $this->newLine(2);
            $this->info("Assets digested in {$endMs}ms");
            $this->newLine(2);
        }
    }
}
