<section>
    <h1 class="text-2xl font-bold mb-8">{{ $title }}</h1>

    <form action="{{ $url }}" method="post" name="{{ $form_name }}" class="block w-full">
        @include('partials.input.csrf')
        @method($method)

        @include($formfields_template, $formfields_data)

        @include('partials.input.button', [
            'type' => 'submit',
            'name' => 'submitButton',
            'title' => $submit,
        ])
    </form>
</section>
