<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class DuskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Browser::macro('removeCsrfInput', function () {
            /** @var Browser $this */
            $this->script('var token = document.querySelector(\'input[name="_token"]\'); token.remove();');

            return $this;
        });

        Browser::macro('waitForCsrfToken', function () {
            /** @var Browser $this */
            $this->waitUntil('return document.querySelector(\'input[name="_token"][data-filled]\') !== null;', 5);

            return $this;
        });
    }
}
