<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\SessionInterface;
use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class LaravelSession implements SessionInterface
{
    private const ERRORS_KEY = 'errors';

    private const DEFAULT_VIEW_ERROR_BAG = 'default';

    private const OLD_INPUT_KEY = '_old_input';

    public function __construct(private Store $laravelSession)
    {
    }

    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    public function token(): ?string
    {
        return $this->laravelSession->token();
    }

    /**
     * Flush the session data and regenerate the ID.
     */
    public function invalidate(): bool
    {
        return $this->laravelSession->invalidate();
    }

    /**
     * Generate a new session identifier.
     */
    public function regenerate(bool $destroy = false): bool
    {
        return $this->laravelSession->regenerate($destroy);
    }

    /**
     * Get the previous URL from the session.
     */
    public function previousUrl(): ?string
    {
        return $this->laravelSession->previousUrl();
    }

    /**
     * Get the intended URL from the session.
     */
    public function intendedUrl(): ?string
    {
        return $this->laravelSession->pull('url.intended');
    }

    /**
     * Flash set of error messages so they are visible on next page load.
     */
    public function flashErrors(array $errors): void
    {
        $errors = new MessageBag($errors);

        $viewErrorBag = $this->laravelSession->get(self::ERRORS_KEY, new ViewErrorBag);

        if ($viewErrorBag instanceof ViewErrorBag === false) {
            $viewErrorBag = new ViewErrorBag;
        }

        $this->laravelSession->flash(
            self::ERRORS_KEY,
            $viewErrorBag->put(self::DEFAULT_VIEW_ERROR_BAG, $errors)
        );
    }

    /**
     * Get the requested item from the flashed input array.
     *
     * @param mixed $default
     * @return mixed
     */
    public function oldInput(string $key, $default = null)
    {
        $oldInputs = $this->laravelSession->get(self::OLD_INPUT_KEY, []);

        return $oldInputs[$key] ?? $default;
    }
}
