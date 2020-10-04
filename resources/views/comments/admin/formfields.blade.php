@include('partials.input.select', [
    'title' => 'Article',
    'name' => 'article_uuid',
    'options' => $articles,
    'value' => $comment['article_uuid'] ?? '',
    'errors' => $errors,
])

@include('partials.input.textarea', [
    'title' => 'Content',
    'name' => 'content',
    'value' => $comment['content'] ?? '',
    'placeholder' => 'Write your comment...',
    'errors' => $errors,
])

@include('partials.input.text', [
    'title' => 'Created at',
    'name' => 'created_at',
    'value' => $comment['created_at'] ?? '',
    'placeholder' => 'yyyy-mm-dd hh:ii:ss',
    'errors' => $errors,
])

@include('partials.input.text', [
    'title' => 'Email',
    'name' => 'email',
    'value' => $comment['email'] ?? '',
    'placeholder' => 'foo@example.com',
    'errors' => $errors,
])

@include('partials.input.text', [
    'title' => 'IP address',
    'name' => 'ip',
    'value' => $comment['ip'] ?? '',
    'placeholder' => '123.123.123.123',
    'errors' => $errors,
])

@include('partials.input.text', [
    'title' => 'Name',
    'name' => 'name',
    'value' => $comment['name'] ?? '',
    'placeholder' => 'name',
    'errors' => $errors,
])

@include('partials.input.radio', [
    'title' => 'Status',
    'name' => 'status',
    'value' => $comment['status'] ?? '',
    'options' => $statuses,
    'errors' => $errors,
])

@include('partials.input.button', [
    'type' => 'submit',
    'name' => 'submit',
    'title' => $submit,
])
