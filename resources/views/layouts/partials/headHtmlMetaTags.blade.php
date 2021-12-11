@php /** @var App\Domain\Utils\MetaData $metaData */ @endphp
<title>{{ $metaData->title() }}{{ __('blog.page_title_suffix') }}</title>
<meta name="title" content="{{ $metaData->title() }}{{ __('blog.page_title_suffix') }}" />
<meta property="og:title" content="{{ $metaData->title() }}{{ __('blog.page_title_suffix') }}" />

<meta name="description" content="{{ $metaData->description() }}" />
<meta property="og:description" content="{{ $metaData->description() }}" />

<meta name="url" content="{{ $metaData->url() }}" />
<meta property="og:url" content="{{ $metaData->url() }}" />

<meta name="og:type" content="{{ $metaData->type() }}">
