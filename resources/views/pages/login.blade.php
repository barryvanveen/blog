@extends('layouts.login')

@section('title', 'Login')

@section('body')
    <section>
        <div class="w-full max-w-xs mx-auto py-10 text-gray-700 text-sm">
            <form action="{{ route('login.post') }}" method="post" name="login" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @include('partials.input.csrf')

                <div class="mb-4">
                    <label class="block font-bold mb-2" for="email">
                        Email address
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="email" type="email" placeholder="Email address">
                    @if ($errors->has('email'))
                        @foreach($errors->get('email') as $error)
                            <p class="text-red-500 italic">{{ $error }}</p>
                        @endforeach
                    @endif
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="password" type="password" placeholder="******************">
                    @if ($errors->has('password'))
                        @foreach($errors->get('password') as $error)
                            <p class="text-red-500 italic">{{ $error }}</p>
                        @endforeach
                    @endif
                </div>

                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submitButton" value="Login">
            </form>
        </div>
    </section>
@endsection
