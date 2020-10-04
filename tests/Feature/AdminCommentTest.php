<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Comments\CommentStatus;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\UserEloquentModel;
use Database\Factories\CommentFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminCommentTest extends TestCase
{
    use RefreshDatabase;

    private UserEloquentModel $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    /** @test */
    public function indexPageIsNotVisibleWithoutAuthentication(): void
    {
        $response = $this->get(route('admin.comments.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function indexPageDisplaysComments(): void
    {
        Auth::login($this->user);

        /** @var CommentEloquentModel[] $comments */
        $comments = CommentFactory::new()->count(3)->create();

        $response = $this->get(route('admin.comments.index'));

        $response->assertOk();
        foreach ($comments as $comment) {
            $response->assertSee($comment->name);
        }
    }

    /** @test */
    public function createPageIsNotVisibleWithoutAuthentication(): void
    {
        $response = $this->get(route('admin.comments.create'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function createComment(): void
    {
        Auth::login($this->user);

        $response = $this->get(route('admin.comments.create'));
        $response->assertOk();

        $response = $this->post(route('admin.comments.store'), []);
        $response->assertRedirect(route('admin.comments.create'));
        $response->assertSessionHasErrors();

        $response = $this->post(route('admin.comments.store'), [
            'article_uuid' => '123123',
            'content' => 'My content',
            'created_at' => '2020-10-04 18:23:58',
            'email' => 'foo@bar.tld',
            'ip' => '123.123.123.123',
            'name' => 'My Name',
            'status' => (string) CommentStatus::published(),
        ]);
        $response->assertRedirect(route('admin.comments.index'));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function updatePageIsNotVisibleWithoutAuthentication(): void
    {
        /** @var CommentEloquentModel $comment */
        $comment = CommentFactory::new()->create();

        $response = $this->put(route('admin.comments.update', ['uuid' => $comment->uuid]));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function updateComment(): void
    {
        Auth::login($this->user);

        /** @var CommentEloquentModel $comment */
        $comment = CommentFactory::new()->create([
            'name' => 'My Old Name',
        ]);

        $response = $this->put(route('admin.comments.update', ['uuid' => $comment->uuid]), [
            'article_uuid' => $comment->article_uuid,
            'content' => $comment->content,
            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            'email' => $comment->email,
            'ip' => $comment->ip,
            'name' => 'My New Name',
            'status' => (string) $comment->status,
        ]);
        $response->assertRedirect(route('admin.comments.index'));
        $response->assertSessionHasNoErrors();

        $response = $this->get(route('admin.comments.index'));
        $response->assertSee('My New Name');
    }
}
