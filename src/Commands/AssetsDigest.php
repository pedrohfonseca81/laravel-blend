<?php

namespace Tyk\LaravelBlend\Commands;

use DateTime;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsDigest extends Command
{
    protected $signature = "assets:digest (file)";

    protected $description = "Digest assets to public folder";

    public function handle(string $file = "resources/js/app.js")
    {
        $beginMs = round(microtime(true) * 1000);

        $esbuildBin = base_path('vendor/bin/esbuild');
        $outFile = public_path('js/app.js');

        $process = new Process([$esbuildBin, "--minify", $file, "--bundle", "--outfile=$outFile", "--analyze"]);

        $process->run();

        $process->wait();

        $endMs = round(microtime(true) * 1000) - $beginMs;

        $this->newLine(2);
        $this->info('dd');
        $this->newLine(2);
    }
}
