@include('pages.partials.input.text', [
    'title' => 'Title',
    'name' => 'title',
    'value' => $article['title'] ?? '',
    'placeholder' => 'Title',
    'errors' => $errors,
])

@include('pages.partials.input.text', [
    'title' => 'Publication date',
    'name' => 'published_at',
    'value' => $article['published_at'] ?? '',
    'placeholder' => 'yyyy-mm-dd hh:ii:ss',
    'errors' => $errors,
])

@include('pages.partials.input.radio', [
    'title' => 'Status',
    'name' => 'status',
    'options' => $statuses,
    'errors' => $errors,
])

@include('pages.partials.input.textarea', [
    'title' => 'Description',
    'name' => 'description',
    'value' => $article['description'] ?? '',
    'placeholder' => 'Summary goes here...',
    'errors' => $errors,
])

@include('pages.partials.input.textarea', [
    'title' => 'Content',
    'name' => 'content',
    'value' => $article['content'] ?? '',
    'placeholder' => 'Content goes here...',
    'errors' => $errors,
])

@include('pages.partials.input.button', [
    'type' => 'submit',
    'name' => 'submit',
    'title' => $submit,
])
