<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Application\Interfaces\SessionInterface;
use Illuminate\Session\Store;

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
}
