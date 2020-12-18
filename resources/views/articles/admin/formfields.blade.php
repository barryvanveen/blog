@include('partials.input.text', [
    'title' => 'Title',
    'name' => 'title',
    'value' => $article['title'] ?? '',
    'placeholder' => 'Title',
    'errors' => $errors,
])

@include('partials.input.text', [
    'title' => 'Publication date',
    'name' => 'published_at',
    'value' => $article['published_at'] ?? '',
    'placeholder' => 'yyyy-mm-dd hh:ii:ss',
    'errors' => $errors,
])

@include('partials.input.radio', [
    'title' => 'Status',
    'name' => 'status',
    'value' => $article['status'] ?? '',
    'options' => $statuses,
    'errors' => $errors,
])

@include('partials.input.editor', [
    'title' => 'Description',
    'name' => 'description',
    'value' => $article['description'] ?? '',
    'placeholder' => 'Summary goes here...',
    'errors' => $errors,
])

@include('partials.input.editor', [
    'title' => 'Content',
    'name' => 'content',
    'value' => $article['content'] ?? '',
    'placeholder' => 'Content goes here...',
    'errors' => $errors,
])
