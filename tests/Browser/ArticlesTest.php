<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Domain\Articles\Models\Article;
use App\Domain\Authors\Models\Author;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ArticlesIndexPage;
use Tests\DuskTestCase;

class ArticlesTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        /** @var Author $author */
        $author = factory(Author::class)->create();

        /** @var Article[] $articles */
        $articles = factory(Article::class, 3)->create([
            'author_id' => $author,
        ]);

        $this->browse(function (Browser $browser) use ($articles) {
            $browser->visit(new ArticlesIndexPage())
                    ->assertSeeIn('@title', 'Articles');

            foreach ($articles as $article) {
                $browser->assertSee($article->title);
            }
        });
    }

    public function testCreate()
    {
        /** @var Author $author */
        $author = factory(Author::class)->create();

        /* @var Article[] $articles */
        factory(Article::class, 1)->create([
            'author_id' => $author,
            'title' => 'Existing article',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit(new ArticlesIndexPage())
                ->assertSee('Existing article')
                ->assertDontSee('Foo title');

            $browser->visit(route('articles.store'))
                ->assertRouteIs('articles.index')
                ->assertSee('Existing article')
                ->assertSee('Foo title');
        });
    }
}
