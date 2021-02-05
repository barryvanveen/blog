<h1>{{ $title }}</h1>

<form action="{{ $url }}" method="post" name="{{ $form_name }}">
    @include('partials.input.csrf')
    @method($method)

    @include($formfields_template, $formfields_data)

    @include('partials.input.button', [
        'type' => 'submit',
        'name' => 'submitButton',
        'title' => $submit,
    ])
</form>
