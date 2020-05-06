@presenter(App\Application\View\CsrfTokenPresenter)

@include('partials.input.hidden', ['name' => '_token', 'value' => $token])
