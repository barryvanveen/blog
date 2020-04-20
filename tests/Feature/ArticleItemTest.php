<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Application\Http\StatusCode;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seePublishedArticle(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = factory(ArticleEloquentModel::class)->states(['published', 'published_in_past'])->create([
            'title' => 'FooArticle',
        ]);

        $response = $this->get(route('articles.show', ['uuid' => $article->uuid, 'slug' => $article->slug]));

        $response->assertOk();
        $response->assertSee($article->title);
    }

    /** @test */
    public function unpublishedArticleResultsIn404ErrorPage(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = factory(ArticleEloquentModel::class)->states(['published', 'published_in_future'])->create([
            'title' => 'FooArticle',
        ]);

        $response = $this->get(route('articles.show', ['uuid' => $article->uuid, 'slug' => $article->slug]));

        $response->assertStatus(StatusCode::STATUS_NOT_FOUND);
        $response->assertSee('Not Found');
    }

    /** @test */
    public function itRedirectsToTheCorrectSlug(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = factory(ArticleEloquentModel::class)->states(['published', 'published_in_past'])->create([
            'uuid' => 'myuuid',
            'slug' => 'my-slug-string',
        ]);

        $response = $this->get(route('articles.show', ['uuid' => $article->uuid, 'slug' => 'wrong-slug']));

        $response->assertStatus(StatusCode::STATUS_MOVED_PERMANENTLY);
        $response->assertRedirect(route('articles.show', ['uuid' => $article->uuid, 'slug' => $article->slug]));
    }
}
