@presenter(App\Application\Pages\View\AdminPagesIndexPresenter)
@extends('layouts.admin')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    @include('partials.admin.index_header', ['title' => $title, 'create_url' => $create_url])

    <table>
        <thead>
        <tr>
            <td>Slug</td>
            <td>Title</td>
            <td>Edit</td>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page['slug'] }}</td>
                <td>{{ $page['title'] }}</td>
                <td><a href="{{ $page['edit_url'] }}">Edit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
