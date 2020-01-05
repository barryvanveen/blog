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

    public function get(string $key, array $replace = []): string
    {
        return (string) $this->translator->get($key, $replace);
    }

    public function choice(string $key, int $number, array $replace = []): string
    {
        return $this->translator->choice($key, $number, $replace);
    }
}
