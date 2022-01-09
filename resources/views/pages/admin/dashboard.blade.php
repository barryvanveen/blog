@presenter(App\Application\View\Admin\DashboardPresenter)

@extends('layouts.admin')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <h1>Hi {{ $name }}</h1>

    <table>
        <thead>
            <tr>
                <td>Type</td>
                <td>Total</td>
                <td>Last update</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Articles</td>
                <td>{{ $stats['articles']['total'] }}</td>
                <td>{{ $stats['articles']['lastUpdate'] }}</td>
            </tr>
            <tr>
                <td>Pages</td>
                <td>{{ $stats['pages']['total'] }}</td>
                <td>{{ $stats['pages']['lastUpdate'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="py-8 flex flex-row justify-between">
        <form action="{{ $clearCacheUrl  }}" method="post" name="clearCache">
            @include('partials.input.csrf')

            @include('partials.input.button', ['type' => 'submit', 'name' => 'submitButton', 'title' => 'Clear cache'])
        </form>

        <form action="{{ $logoutUrl }}" method="post" name="logout">
            @include('partials.input.csrf')

            @include('partials.input.button', ['type' => 'submit', 'name' => 'submitButton', 'title' => 'Logout'])
        </form>
    </div>
@endsection
