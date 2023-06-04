<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Events\LockoutWasTriggered;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Exceptions\LockoutException;
use App\Application\Auth\Requests\LoginRequestInterface;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Http\StatusCode;
use App\Application\Interfaces\CommandBusInterface;
use App\Application\Interfaces\EventBusInterface;
use App\Application\Interfaces\GuardInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    public function __construct(
        private ResponseBuilderInterface $responseBuilder,
        private CommandBusInterface $commandBus,
        private GuardInterface $guard,
        private EventBusInterface $eventBus,
    ) {
    }

    public function form(): ResponseInterface
    {
        if ($this->guard->authenticated()) {
            return $this->responseBuilder->redirect('admin.dashboard');
        }

        return $this->responseBuilder->ok('pages.login');
    }

    public function login(LoginRequestInterface $request): ResponseInterface
    {
        if ($this->guard->authenticated()) {
            return $this->responseBuilder->redirect('admin.dashboard');
        }

        $command = new Login(
            $request->email(),
            $request->password(),
            $request->remember(),
            $request->ip()
        );

        try {
            $this->commandBus->dispatch($command);
        } catch (FailedLoginException) {
            return $this->responseBuilder->redirectBack(
                StatusCode::STATUS_FOUND,
                [
                    'email' => ['These credentials do not match our records.'],
                ]
            );
        } catch (LockoutException $e) {
            $this->eventBus->dispatch(
                new LockoutWasTriggered($request->email(), $request->ip())
            );

            return $this->responseBuilder->redirectBack(
                StatusCode::STATUS_FOUND,
                [
                    'email' => ['Too many login attempts. Please try again in ' . $e->tryAgainIn() . ' seconds.'],
                ]
            );
        }

        return $this->responseBuilder->redirectIntended('admin.dashboard');
    }

    public function logout(): ResponseInterface
    {
        $command = new Logout();

        $this->commandBus->dispatch($command);

        return $this->responseBuilder->redirect('home');
    }
}
