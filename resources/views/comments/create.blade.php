@presenter(App\Application\Articles\View\ArticlesCreateCommentPresenter)

<form action="{{ $create_comment_url }}" method="post" name="comment" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @include('partials.input.csrf')

    @include('partials.input.hidden', [
       'name' => 'youshouldnotfillthisfield',
       'value' => '',
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
