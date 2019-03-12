<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\TranslatorInterface;
use Illuminate\Contracts\Translation\Translator;

final class LaravelTranslator implements TranslatorInterface
{
    /** @var Translator */
    private $translator;

    public function __construct(Translator $laravelTranslator)
    {
        $this->translator = $laravelTranslator;
    }

    public function trans(string $key, array $replace = [], $locale = null): string
    {
        return (string) $this->translator->trans($key, $replace, $locale);
    }

    public function transChoice(string $key, int $number, array $replace = [], $locale = null): string
    {
        return $this->translator->transChoice($key, $number, $replace, $locale);
    }

    public function getLocale(): string
    {
        return (string) $this->translator->getLocale();
    }

    public function setLocale(string $locale): void
    {
        $this->translator->setLocale($locale);
    }
}
