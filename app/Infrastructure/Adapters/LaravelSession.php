<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\SessionInterface;
use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class LaravelSession implements SessionInterface
{
    /** @var Store */
    private $laravelSession;

    public function __construct(Store $laravelSession)
    {
        $this->laravelSession = $laravelSession;
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
     *
     * @return bool
     */
    public function invalidate(): bool
    {
        return $this->laravelSession->invalidate();
    }

    /**
     * Generate a new session identifier.
     *
     * @param  bool $destroy
     * @return bool
     */
    public function regenerate(bool $destroy = false): bool
    {
        return $this->laravelSession->regenerate($destroy);
    }

    /**
     * Get the previous URL from the session.
     *
     * @return string|null
     */
    public function previousUrl(): ?string
    {
        return $this->laravelSession->previousUrl();
    }

    /**
     * Get the intended URL from the session.
     *
     * @return string|null
     */
    public function intendedUrl(): ?string
    {
        return $this->laravelSession->pull('url.intended');
    }

    /**
     * Flash set of error messages so they are visible on next page load.
     *
     * @param array $errors
     */
    public function flashErrors(array $errors): void
    {
        $errors = new MessageBag($errors);

        $viewErrorBag = $this->laravelSession->get('errors', new ViewErrorBag);

        if (! $viewErrorBag instanceof ViewErrorBag) {
            $viewErrorBag = new ViewErrorBag;
        }

        $this->laravelSession->flash('errors', $viewErrorBag->put('default', $errors));
    }
}
