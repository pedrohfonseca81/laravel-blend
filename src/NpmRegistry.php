<?php

namespace Tyk\LaravelBlend;

use Error;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tyk\LaravelBlend\Trait\WithKernelPlatform;

class NpmRegistry
{
    use WithKernelPlatform;

    public $result;

    public $path;

    private $defaultVersion = "0.16.0";

    public $public_key = '';

    private $public_key_pem = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAE1Olb3zMAFFxXKHiIkQO5cJ3Yhl5i
    6UPp+IhuteBJbuHcA5UogKo0EWtlWwW6KSaKoTNEYL7JlCQiVnkhBktUgg==
    -----END PUBLIC KEY-----
    EOD;

    private $public_key_id = "SHA256:jl3bwswu80PjjokCgh0o2w5c2U4LhQAE57gj9cz1kzA";

    public function getPackage(string $version)
    {
        $body = Http::esbuild()->get($this->getUserPlatform())->collect();
        $version = collect($body['versions'])->get($version);

        $firstSignature = collect($version['dist']['signatures'])->first(fn ($sig) => $sig['keyid'] == $this->public_key_id);

        $message = "{$version['_id']}:{$version['dist']['integrity']}";

        $this->verifySignature($message, $firstSignature['sig']);

        return [
            'id' => $body->get('_id'),
            'rev' => $body->get('_rev'),
            'version' => $version,
        ];
    }

    public function download($version)
    {
        $version = $version ?: $this->defaultVersion;

        $this->result = $this->getPackage($version);

        $tarballRaw = Http::esbuild()->get($this->result['version']['dist']['tarball'])->body();
        $this->path = "esbuild-{$this->getUserPlatform()}-{$version}.tgz";

        Storage::disk('local')->put($this->path, $tarballRaw);

        return $this;
    }

    public function verifySignature($message, $signature)
    {
        $decodedSignature = base64_decode($signature);
        $publicKey = openssl_pkey_get_public($this->public_key_pem);

        $status = openssl_verify($message, $decodedSignature, $publicKey, "sha256");

        if($status !== 1) {
            return throw new Error("Invalid signature");
        }

        return $status;
    }
}
