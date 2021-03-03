<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Application\Http\StatusCode;
use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Database\Factories\ArticleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seePublishedArticle(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()->published()->publishedInPast()->create([
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
        $article = ArticleFactory::new()->published()->publishedInFuture()->create([
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
        $article = ArticleFactory::new()->published()->publishedInPast()->create([
            'uuid' => 'myuuid',
            'slug' => 'my-slug-string',
        ]);

        $response = $this->get(route('articles.show', ['uuid' => $article->uuid, 'slug' => 'wrong-slug']));

        $response->assertStatus(StatusCode::STATUS_MOVED_PERMANENTLY);
        $response->assertRedirect(route('articles.show', ['uuid' => $article->uuid, 'slug' => $article->slug]));
    }

    /** @test */
    public function itFailsToCreateCommentWithBadInput(): void
    {
        $response = $this->postJson(route('comments.store'), [
            'content' => 'myContent',
            'email' => 'not an email',
            'ip' => '123.123.123.123',
            'name' => 'My Name',
        ]);

        $response->assertStatus(StatusCode::STATUS_BAD_REQUEST);
        $response->assertJson([
            'email' => [
                'This is not a valid email address',
            ],
        ]);
    }

    /** @test */
    public function itFailsToCreateCommentIfAnArticleUuidIsIncorrect(): void
    {
        $response = $this->postJson(route('comments.store'), [
            'article_uuid' => 'foooo',
            'content' => 'myContent',
            'email' => 'john@example.com',
            'ip' => '123.123.123.123',
            'name' => 'My Name',
        ]);

        $response->assertStatus(StatusCode::STATUS_BAD_REQUEST);
        $response->assertJson([
            'error' => 'Comment could not be created.',
        ]);
    }

    /** @test */
    public function itFailsToCreateCommentIfAnHoneypotIsNotEmpty(): void
    {
        $response = $this->postJson(route('comments.store'), [
            'article_uuid' => 'foooo',
            'content' => 'myContent',
            'email' => 'john@example.com',
            'ip' => '123.123.123.123',
            'name' => 'My Name',
            'youshouldnotfillthisfield' => 'Fooo',
        ]);

        $response->assertStatus(StatusCode::STATUS_BAD_REQUEST);
        $response->assertJson([
            'error' => 'Comment could not be created.',
        ]);
    }


    /** @test */
    public function itCreatesComment(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()->publishedInPast()->create([
            'uuid' => 'myuuid',
            'slug' => 'my-slug-string',
        ]);

        $response = $this->postJson(route('comments.store'), [
            'article_uuid' => $article->uuid,
            'content' => 'myContent',
            'email' => 'john@example.com',
            'ip' => '123.123.123.123',
            'name' => 'My Name',
        ]);

        $response->assertStatus(StatusCode::STATUS_OK);
        $response->assertJson([
            'success' => true,
        ]);
    }
}
