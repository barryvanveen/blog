<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\Auth\Commands\Login;
use App\Application\Auth\Commands\Logout;
use App\Application\Core\CommandBusInterface;
use App\Infrastructure\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function form()
    {
        // todo: return ViewModel
        return view('pages.login');
    }

    /**
     * @param LoginRequest $request
     * @param CommandBusInterface $bus
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request, CommandBusInterface $bus)
    {
        $command = new Login(
            $request->input('email'),
            $request->input('password'),
            $request->filled('remember'),
            $request->ip()
        );

        $bus->dispatch($command);

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * @param CommandBusInterface $bus
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(CommandBusInterface $bus)
    {
        $command = new Logout();

        $bus->dispatch($command);

        return redirect(route('home'));
    }
}
