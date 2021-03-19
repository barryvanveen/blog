<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Infrastructure\Eloquent\ArticleEloquentModel;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use Database\Factories\ArticleFactory;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ArticleItemPage;
use Tests\DuskTestCase;

class ArticleItemTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function visitArticlePageAndCreateComment(): void
    {
        /** @var ArticleEloquentModel $article */
        $article = ArticleFactory::new()
            ->publishedInPast()
            ->create();

        /** @var CommentEloquentModel $comment */
        $comment = CommentFactory::new()
            ->published()
            ->forArticle($article->uuid)
            ->create();

        $this->browse(function (Browser $browser) use ($article, $comment) {
            $browser
                ->visit(new ArticleItemPage($article->uuid, $article->slug))
                ->assertSeeIn('@articleHeading', $article->title)
                ->assertSee($comment->name)
                ->assertNumberOfElements('div[itemprop="comment"]', 1)

                // submit empty form and see error messages
                ->click('@submit')
                ->waitForSubmitButtonEnabled()
                ->assertSeeIn('@nameError', 'Please fill in your name')
                ->assertSeeIn('@emailError', 'Please fill in a valid email address')
                ->assertSeeIn('@contentError', 'Please fill in a message for your comment')

                // submit valid data
                ->type('@name', 'Foo Name')
                ->type('@email', 'foo@example.com')
                ->type('@content', 'Foo content')
                ->click('@submit')

                // get back to the article page and see new comment
                ->waitForSubmitButtonEnabled()
                ->assertSee('Foo Name')
                ->assertNumberOfElements('div[itemprop="comment"]', 2);
        });
    }
}
