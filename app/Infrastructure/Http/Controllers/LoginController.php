<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Commands\Logout;
use App\Application\Auth\Exceptions\FailedLoginException;
use App\Application\Auth\Exceptions\LockoutException;
use App\Application\Core\CommandBusInterface;
use App\Application\Interfaces\TranslatorInterface;
use App\Infrastructure\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function form(): View
    {
        return $this->viewFactory->make('pages.login');
    }

    public function login(LoginRequest $request, CommandBusInterface $bus, TranslatorInterface $translator): RedirectResponse
    {
        /**
         * @psalm-suppress PossiblyInvalidArgument
         */
        $command = new Login(
            $request->input('email'),
            $request->input('password'),
            $request->filled('remember'),
            (string) $request->ip()
        );

        try {
            $bus->dispatch($command);
        } catch (FailedLoginException $e) {
            return redirect()->back()->withErrors([
                'email' => [$translator->get('auth.failed')],
            ]);
        } catch (LockoutException $e) {
            return redirect()->back()->withErrors([
                'email' => [$translator->get('auth.throttle', ['seconds' => $e->tryAgainIn()])],
            ]);
        }

        return $this->redirector->intended(route('admin.dashboard'));
    }

    public function logout(CommandBusInterface $bus): RedirectResponse
    {
        $command = new Logout();

        $bus->dispatch($command);

        return $this->redirector->route('home');
    }
}
