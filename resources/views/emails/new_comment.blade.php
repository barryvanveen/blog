@component('mail::message')
# New comment

A new comment was posted.

* Name: {{ $name }}
* Email: {{ $email }}
<br>

## Content

{!! nl2br(e($content)) !!}

@component('mail::button', ['url' => $admin_url])
    Admin panel
@endcomponent

Take care,<br>
Barry
@endcomponent
