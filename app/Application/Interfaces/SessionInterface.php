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
     */
    public function invalidate(): bool;

    /**
     * Generate a new session identifier.
     */
    public function regenerate(bool $destroy = false): bool;

    /**
     * Get the previous URL from the session.
     */
    public function previousUrl(): ?string;

    /**
     * Get the intended URL from the session.
     */
    public function intendedUrl(): ?string;

    /**
     * Flash set of error messages so they are visible on next page load.
     */
    public function flashErrors(array $errors): void;

    /**
     * Get the requested item from the flashed input array.
     *
     * @return mixed
     */
    public function oldInput(string $key, mixed $default = null);
}
