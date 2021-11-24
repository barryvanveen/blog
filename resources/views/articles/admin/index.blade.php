@presenter(App\Application\Articles\View\AdminArticlesIndexPresenter)
@extends('layouts.admin')

@section('title', $title)

@section('body')
    @include('partials.admin.index_header', ['title' => $title, 'create_url' => $create_url])

    <table>
        <thead>
            <tr>
                <td>UUID</td>
                <td>Title</td>
                <td>Published</td>
                <td>Edit</td>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr class="{{ $article['is_online'] ? 'online' : 'offline' }}">
                    <td>{{ $article['uuid'] }}</td>
                    <td>{{ $article['title'] }}</td>
                    <td>{{ $article['published_at'] }}</td>
                    <td><a href="{{ $article['edit_url'] }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
