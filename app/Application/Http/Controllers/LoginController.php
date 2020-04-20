<?php

declare(strict_types=1);

namespace App\Application\Http\Controllers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Exceptions\LockoutException;
use App\Application\Auth\Requests\LoginRequestInterface;
use App\Application\Core\CommandBusInterface;
use App\Application\Core\ResponseBuilderInterface;
use App\Application\Interfaces\SessionInterface;
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

    /** @var SessionInterface */
    private $session;

    public function __construct(
        ResponseBuilderInterface $responseBuilder,
        CommandBusInterface $commandBus,
        TranslatorInterface $translator,
        SessionInterface $session
    ) {
        $this->responseBuilder = $responseBuilder;
        $this->commandBus = $commandBus;
        $this->translator = $translator;
        $this->session = $session;
    }

    public function form(): ResponseInterface
    {
        return $this->responseBuilder->ok('pages.login');
    }

    public function login(LoginRequestInterface $request): ResponseInterface
    {
        $command = new Login(
            $request->email(),
            $request->password(),
            $request->remember(),
            $request->ip()
        );

        try {
            $this->commandBus->dispatch($command);
        } catch (FailedLoginException $e) {
            $this->session->flashErrors([
                'email' => [$this->translator->get('auth.failed')],
            ]);

            return $this->responseBuilder->redirectBack();
        } catch (LockoutException $e) {
            $this->session->flashErrors([
                'email' => $this->translator->get('auth.throttle', ['seconds' => $e->tryAgainIn()]),
            ]);

            return $this->responseBuilder->redirectBack();
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
