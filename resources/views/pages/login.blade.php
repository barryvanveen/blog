@extends('layouts.base')

@section('body')
    <section>
        <h1>Login</h1>

        <form action="{{ route('login.post') }}" method="post" name="login">
            @csrf

            <label>
                Email address
                <input type="email" name="email">
            </label>

            <label>
                Password
                <input type="password" name="password">
            </label>

            <input type="submit" name="submit" value="Login">
        </form>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection