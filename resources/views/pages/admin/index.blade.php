@presenter(App\Application\Pages\View\AdminPagesIndexPresenter)
@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <div class="flex justify-between items-center">
        <h1 class="">{{ $title }}</h1>

        <a href="{{ $create_url }}" class="bg-blue-500 hover:bg-blue-700 text-white hover:text-white font-bold no-underline hover:no-underline py-2 px-4 rounded focus:outline-none focus:shadow-outline">New</a>
    </div>

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
