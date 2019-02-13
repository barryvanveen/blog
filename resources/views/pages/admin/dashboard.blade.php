@extends('layouts.base')

@section('body')
    <section>
        <h1>Hi {{ Auth::user()->name }}</h1>

        <form action="{{ route('logout.post') }}" method="post" name="logout">
            @csrf

            <input type="submit" name="submit" value="Logout">
        </form>
    </section>
@endsection