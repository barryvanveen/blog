<?php
use App\Application\Articles\ViewModels\ArticlesItemViewModel;

/** @var $model ArticlesItemViewModel */
$model = app()->make(ArticlesItemViewModel::class);
?>

@extends('layouts.base')

@section('body')

    <article>
        <h1>{{ $model->article()->title() }}</h1>
        <p>{{ $model->article()->publishedAt()->format('Y-m-d H:i:s') }}, 0 comments</p>
        <p>{{ $model->article()->content() }}</p>
    </article>

@endsection
