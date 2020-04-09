@php /** @var App\Domain\Utils\MetaData $metaData */ @endphp
<title>{{ $metaData->title() }}</title>
<meta name="title" content="{{ $metaData->title() }}" />
<meta property="og:title" content="{{ $metaData->title() }}" />

<meta name="description" content="{{ $metaData->description() }}" />
<meta property="og:description" content="{{ $metaData->description() }}" />

<meta name="url" content="{{ $metaData->url() }}" />
<meta property="og:url" content="{{ $metaData->url() }}" />

<meta name="og:type" content="{{ $metaData->type() }}">
