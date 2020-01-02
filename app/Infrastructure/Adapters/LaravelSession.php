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
     * Get the name of the session.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->laravelSession->getName();
    }

    /**
     * Get the current session ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->laravelSession->getId();
    }

    /**
     * Set the session ID.
     *
     * @param  string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->laravelSession->setId($id);
    }

    /**
     * Start the session, reading the data from a handler.
     *
     * @return bool
     */
    public function start(): bool
    {
        return $this->laravelSession->start();
    }

    /**
     * Save the session data to storage.
     *
     * @return void
     */
    public function save(): void
    {
        $this->laravelSession->save();
    }

    /**
     * Get all of the session data.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->laravelSession->all();
    }

    /**
     * Checks if a key exists.
     *
     * @param  string|array $key
     * @return bool
     */
    public function exists($key): bool
    {
        return $this->laravelSession->exists($key);
    }

    /**
     * Checks if an a key is present and not null.
     *
     * @param  string|array $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->laravelSession->has($key);
    }

    /**
     * Get an item from the session.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->laravelSession->get($key, $default);
    }

    /**
     * Put a key / value pair or array of key / value pairs in the session.
     *
     * @param  string|array $key
     * @param  mixed $value
     * @return void
     */
    public function put($key, $value = null): void
    {
        $this->laravelSession->put($key, $value);
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
     * Remove an item from the session, returning its value.
     *
     * @param  string $key
     * @return mixed
     */
    public function remove(string $key)
    {
        return $this->laravelSession->remove($key);
    }

    /**
     * Remove one or many items from the session.
     *
     * @param  string|array $keys
     * @return void
     */
    public function forget($keys): void
    {
        $this->laravelSession->forget($keys);
    }

    /**
     * Remove all of the items from the session.
     *
     * @return void
     */
    public function flush(): void
    {
        $this->laravelSession->flush();
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
     * Generate a new session ID for the session.
     *
     * @param  bool $destroy
     * @return bool
     */
    public function migrate(bool $destroy = false): bool
    {
        return $this->laravelSession->migrate($destroy);
    }

    /**
     * Determine if the session has been started.
     *
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->laravelSession->isStarted();
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
     * Set the "previous" URL in the session.
     *
     * @param  string $url
     * @return void
     */
    public function setPreviousUrl(string $url): void
    {
        $this->laravelSession->setPreviousUrl($url);
    }
}
