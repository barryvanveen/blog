<?php

declare(strict_types=1);

namespace App\Application\Core;

use App\Application\Exceptions\EventListenerException;

abstract class BaseEventListener implements EventListenerInterface
{
    /**
     * @throws EventListenerException
     */
    public function handle(EventInterface $event): void
    {
        $method = $this->getHandleMethod($event);

        if (! method_exists($this, $method)) {
            throw EventListenerException::handleMethodIsMissing($method);
        }

        $this->$method($event);
    }

    private function getHandleMethod(EventInterface $event): string
    {
        $classParts = explode('\\', $event::class);

        return 'handle' . end($classParts);
    }
}
