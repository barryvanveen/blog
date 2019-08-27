<?php
use App\Application\Articles\ViewModels\ArticlesIndexViewModel;

/** @var $model ArticlesIndexViewModel */
$model = app()->make(ArticlesIndexViewModel::class);
?>

<section>
    <h1 id="title">Articles</h1>

    <ul>
        @each('blocks.articles.partials.card', $model->articles(), 'article')
    </ul>
</section>
