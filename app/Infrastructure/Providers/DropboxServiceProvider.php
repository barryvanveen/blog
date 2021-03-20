<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('dropbox', function (Application $app, array $config) {
            $client = new Client(
                $config['authorizationToken']
            );

            return new Filesystem(new DropboxAdapter($client));
        });
    }
}
