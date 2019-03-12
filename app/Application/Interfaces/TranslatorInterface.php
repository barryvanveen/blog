<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface TranslatorInterface
{
    /**
     * Get the translation for a given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    public function trans(string $key, array $replace = [], $locale = null): string;

    /**
     * Get a translation according to an integer value.
     *
     * @param  string  $key
     * @param  int     $number
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    public function transChoice(string $key, int $number, array $replace = [], $locale = null): string;

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getLocale(): string;

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale(string $locale): void;
}
