@presenter(App\Application\View\CsrfTokenPresenter)

@include('pages.partials.input.hidden', ['name' => '_token', 'value' => $token])
