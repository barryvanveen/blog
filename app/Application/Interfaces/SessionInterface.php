<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

interface SessionInterface
{
    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    public function token(): ?string;

    /**
     * Flush the session data and regenerate the ID.
     *
     * @return bool
     */
    public function invalidate(): bool;

    /**
     * Generate a new session identifier.
     *
     * @param  bool  $destroy
     * @return bool
     */
    public function regenerate(bool $destroy = false): bool;

    /**
     * Get the previous URL from the session.
     *
     * @return string|null
     */
    public function previousUrl(): ?string;
}
