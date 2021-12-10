@presenter(App\Application\Comments\View\AdminCommentsIndexPresenter)
@extends('layouts.admin')

@section('title', $title)

@section('body')
    @include('partials.admin.index_header', ['title' => $title, 'create_url' => $create_url])

    <table>
        <thead>
            <tr>
                <td>UUID</td>
                <td>Name</td>
                <td>Content</td>
                <td>Created</td>
                <td>Edit</td>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr class="{{ $comment['is_online'] ? 'online' : 'offline' }}">
                    <td>{{ $comment['uuid'] }}</td>
                    <td>{{ $comment['name'] }}</td>
                    <td>{{ $comment['content'] }}</td>
                    <td>{{ $comment['created_at'] }}</td>
                    <td><a href="{{ $comment['edit_url'] }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
