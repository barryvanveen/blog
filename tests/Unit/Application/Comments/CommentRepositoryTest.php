<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments;

use App\Application\Comments\CommentRepository;
use App\Domain\Comments\Comment;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\CommentMapper;
use Carbon\Carbon;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\CommentRepository
 */
class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CommentRepository $repository;

    private CommentFactory $commentFactory;

    public function setUp(): void
    {
        parent::setUp();

        $queryBuilder = new LaravelQueryBuilder(CommentEloquentModel::query());
        $commentMapper = new CommentMapper();

        $this->repository = new CommentRepository($queryBuilder, $commentMapper);

        $this->commentFactory = CommentFactory::new();
    }

    /** @test */
    public function itRetrievesAllCommentsInTheCorrectOrder(): void
    {
        // arrange
        $this->commentFactory->create([
            'content' => 'comment1',
            'created_at' => Carbon::now()->subDays(4),
        ]);
        $this->commentFactory->create([
            'content' => 'comment2',
            'created_at' => Carbon::now()->subDays(1),
        ]);
        $this->commentFactory->create([
            'content' => 'comment3',
            'created_at' => Carbon::now()->subDays(2),
        ]);
        $this->commentFactory->create([
            'content' => 'comment4',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // act
        /** @var Comment[] $comments */
        $comments = $this->repository->allOrdered()->toArray();

        // assert
        $this->assertCount(4, $comments);
        $this->assertEquals('comment2', $comments[0]->content());
        $this->assertEquals('comment3', $comments[1]->content());
        $this->assertEquals('comment4', $comments[2]->content());
        $this->assertEquals('comment1', $comments[3]->content());
    }

    /** @test */
    public function itRetrievesACommentByUUID(): void
    {
        // arrange
        /** @var CommentEloquentModel $eloquentComment */
        $eloquentComment = $this->commentFactory->create();
        $comment = $this->repository->getByUuid($eloquentComment->uuid);

        // assert
        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals($eloquentComment->uuid, $comment->uuid());
        $this->assertEquals($eloquentComment->content, $comment->content());
    }
}
