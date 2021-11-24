@presenter(App\Application\Articles\View\ArticlesCreateCommentPresenter)

<noscript class="block bg-red-300 rounded p-4 my-4">
    Sorry, posting comments will only work if you enable Javascript.
</noscript>

<form action="{{ $create_comment_url }}" method="post" name="comment" class="bg-gray-300 drop-shadow-md rounded-md p-4 mb-4">
    @include('partials.input.csrf')

    @include('partials.input.honeypot', [
       'title' => 'You should not fill this field',
       'name' => 'youshouldnotfillthisfield',
       'value' => '',
       'placeholder' => '',
    ])

    @include('partials.input.hidden', [
       'name' => 'article_uuid',
       'value' => $article_uuid,
    ])

    @include('partials.input.text', [
        'title' => 'Name',
        'name' => 'name',
        'placeholder' => '',
        'value' => '',
    ])

    @include('partials.input.text', [
        'title' => 'Email address (not visible to others)',
        'name' => 'email',
        'placeholder' => '',
        'value' => '',
    ])

    @include('partials.input.textarea', [
        'title' => 'Message',
        'name' => 'content',
        'placeholder' => '',
        'value' => '',
    ])

    @include('partials.input.button', [
        'type' => 'submit',
        'name' => 'submitButton',
        'title' => 'Submit',
    ])
</form>
