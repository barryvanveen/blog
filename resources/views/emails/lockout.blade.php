@component('mail::message')
# Lockout triggered

Somebody logged in too many times and was locked out because of it.

* Email: {{ $email }}
* IP: {{ $ip }}

@component('mail::button', ['url' => $admin_url])
    Admin panel
@endcomponent

Take care,<br>
Barry
@endcomponent
