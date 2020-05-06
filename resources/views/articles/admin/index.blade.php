@presenter(App\Application\Articles\View\AdminArticlesIndexPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <div class="flex justify-between items-center">
        <h1 class="">{{ $title }}</h1>

        <a href="{{ $create_url }}" class="bg-blue-500 hover:bg-blue-700 text-white hover:text-white font-bold no-underline hover:no-underline py-2 px-4 rounded focus:outline-none focus:shadow-outline">New</a>
    </div>

    <table>
        <thead>
            <tr>
                <td>UUID</td>
                <td>Title</td>
                <td>Online</td>
                <td>Published</td>
                <td>Edit</td>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>{{ $article['uuid'] }}</td>
                    <td>{{ $article['title'] }}</td>
                    <td>{{ $article['status'] }}</td>
                    <td>{{ $article['published_at'] }}</td>
                    <td><a href="{{ $article['edit_url'] }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
