<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use Database\Factories\ArticleFactory;
use Database\Factories\PageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeSitemap(): void
    {
        /** @var ArticleEloquentModel $article1 */
        $article1 = ArticleFactory::new()
            ->published()
            ->publishedInPast()
            ->create();

        /** @var ArticleEloquentModel $article2 */
        $article2 = ArticleFactory::new()
            ->unpublished()
            ->publishedInFuture()
            ->create();

        PageFactory::new()->title('About')->create();
        PageFactory::new()->title('Books')->create();
        PageFactory::new()->title('Home')->create();

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertSee(route('home'));
        $response->assertSee(route('about'));
        $response->assertSee(route('books'));
        $response->assertSee(route('articles.show', ['uuid' => $article1->uuid, 'slug' => $article1->slug]));
        $response->assertDontSee(route('articles.show', ['uuid' => $article2->uuid, 'slug' => $article2->slug]));
    }
}
