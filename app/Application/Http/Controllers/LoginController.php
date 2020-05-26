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
use App\Application\Interfaces\TranslatorInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    /** @var ResponseBuilderInterface */
    private $responseBuilder;

    /** @var CommandBusInterface */
    private $commandBus;

    /** @var TranslatorInterface */
    private $translator;

    /** @var GuardInterface */
    private $guard;

    /** @var EventBusInterface */
    private $eventBus;

    public function __construct(
        ResponseBuilderInterface $responseBuilder,
        CommandBusInterface $commandBus,
        TranslatorInterface $translator,
        GuardInterface $guard,
        EventBusInterface $eventBus
    ) {
        $this->responseBuilder = $responseBuilder;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->guard = $guard;
        $this->eventBus = $eventBus;
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
        } catch (FailedLoginException $e) {
            return $this->responseBuilder->redirectBack(
                StatusCode::STATUS_FOUND,
                [
                    'email' => [$this->translator->get('auth.failed')],
                ]
            );
        } catch (LockoutException $e) {
            $this->eventBus->dispatch(
                new LockoutWasTriggered($request->email(), $request->ip())
            );

            return $this->responseBuilder->redirectBack(
                StatusCode::STATUS_FOUND,
                [
                    'email' => $this->translator->get('auth.throttle', ['seconds' => $e->tryAgainIn()]),
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
