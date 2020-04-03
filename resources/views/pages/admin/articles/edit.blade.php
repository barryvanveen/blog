@presenter(App\Application\Articles\View\AdminArticlesEditPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $update_article_url }}" method="post" name="edit">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-bold mb-2" for="title">
                Title
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="title" type="text" placeholder="Title" value="{{ $article['title'] }}">
            @if ($errors->has('title'))
                @foreach($errors->get('title') as $error)
                    <p class="text-red-500 italic">{{ $error }}</p>
                @endforeach
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-2" for="published_at">
                Publication date
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="published_at" type="text" placeholder="yyyy-mm-dd hh:ii:ss" value="{{ $article['published_at'] }}">
            @if ($errors->has('published_at'))
                @foreach($errors->get('published_at') as $error)
                    <p class="text-red-500 italic">{{ $error }}</p>
                @endforeach
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-2" for="status">
                Status
            </label>
            <div class="mt-2">
                @foreach($statuses as $status)
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="{{ $status['value'] }}" @if($status['checked'])checked="checked"@endif>
                        <span class="ml-2">{{ $status['title'] }}</span>
                    </label>
                @endforeach
{{--                <label class="inline-flex items-center ml-6">--}}
{{--                    <input type="radio" name="status" value="published">--}}
{{--                    <span class="ml-2">Business</span>--}}
{{--                </label>--}}
            </div>
{{--            <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="status" type="text" value="{{ $article['status'] }}">--}}
            @if ($errors->has('status'))
                @foreach($errors->get('status') as $error)
                    <p class="text-red-500 italic">{{ $error }}</p>
                @endforeach
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-2" for="description">
                Description
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="description" type="textarea" placeholder="Summary goes here...">{{ $article['description'] }}</textarea>
            @if ($errors->has('description'))
                @foreach($errors->get('description') as $error)
                    <p class="text-red-500 italic">{{ $error }}</p>
                @endforeach
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-2" for="content">
                Content
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" name="content" type="textarea" placeholder="Content goes here...">{{ $article['content'] }}</textarea>
            @if ($errors->has('content'))
                @foreach($errors->get('content') as $error)
                    <p class="text-red-500 italic">{{ $error }}</p>
                @endforeach
            @endif
        </div>

        <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="Edit">
    </form>
@endsection
