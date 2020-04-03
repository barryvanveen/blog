@presenter(App\Application\Articles\View\AdminArticlesIndexPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

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
