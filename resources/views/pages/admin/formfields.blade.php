@include('partials.input.text', [
    'title' => 'Title',
    'name' => 'title',
    'value' => $page['title'] ?? '',
    'placeholder' => 'Title',
    'errors' => $errors,
])

@include('partials.input.text', [
    'title' => 'Slug',
    'name' => 'slug',
    'value' => $page['slug'] ?? '',
    'placeholder' => 'my-slug',
    'errors' => $errors,
])

@include('partials.input.editor', [
    'title' => 'Description',
    'name' => 'description',
    'value' => $page['description'] ?? '',
    'placeholder' => 'Summary goes here...',
    'errors' => $errors,
])

@include('partials.input.editor', [
    'title' => 'Content',
    'name' => 'content',
    'value' => $page['content'] ?? '',
    'placeholder' => 'Content goes here...',
    'errors' => $errors,
])
