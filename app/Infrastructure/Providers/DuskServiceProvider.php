<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert;

class DuskServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            return;
        }

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

        Browser::macro('waitForPreviewRendered', function (string $name) {
            /** @var Browser $this */
            $this->waitUntil('return document.querySelector(\'#editor-preview-'.$name.'[data-filled]\') !== null;', 5);

            return $this;
        });

        Browser::macro('waitForSubmitButtonEnabled', function () {
            /** @var Browser $this */
            $this->waitUntil('return document.querySelector(\'input[type="submit"]:not([disabled])\') !== null;', 5);

            return $this;
        });

        Browser::macro('assertNumberOfElements', function (string $selector, int $expectedCount) {
            /** @var Browser $this */
            $elements = $this->elements($selector);

            Assert::assertCount($expectedCount, $elements);

            return $this;
        });
    }
}
