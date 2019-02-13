<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    // todo add ThrottlesLogins

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->redirectTo = route('admin.dashboard');
    }

    public function form()
    {
        // todo: return ViewModel
        return view('pages.login');
    }
}
