<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments;

use App\Application\Comments\CommentRepository;
use App\Application\Comments\Events\CommentWasCreated;
use App\Application\Comments\Events\CommentWasUpdated;
use App\Application\Interfaces\EventBusInterface;
use App\Domain\Comments\Comment;
use App\Infrastructure\Adapters\LaravelQueryBuilder;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\CommentMapper;
use Carbon\Carbon;
use Database\Factories\CommentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\CommentRepository
 */
class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var ObjectProphecy|EventBusInterface */
    private ObjectProphecy $eventBus;

    private CommentRepository $repository;
    private CommentFactory $commentFactory;

    public function setUp(): void
    {
        parent::setUp();

        $queryBuilder = new LaravelQueryBuilder(CommentEloquentModel::query());
        $commentMapper = new CommentMapper();
        $this->eventBus = $this->prophesize(EventBusInterface::class);

        $this->repository = new CommentRepository(
            $queryBuilder,
            $commentMapper,
            $this->eventBus->reveal()
        );

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

    /** @test */
    public function itCreatesAndUpdatesAComment(): void
    {
        // arrange
        $comment = $this->getComment([
            'name' => 'My Name',
        ]);

        // act
        $this->repository->insert($comment);

        // assert
        $this->assertDatabaseHas('comments', ['uuid' => $comment->uuid()]);
        $this->eventBus->dispatch(Argument::type(CommentWasCreated::class))->shouldHaveBeenCalled();

        // act again
        $updatedComment = new Comment(
            $comment->articleUuid(),
            $comment->content(),
            $comment->createdAt(),
            $comment->email(),
            $comment->ip(),
            'My New Name',
            $comment->status(),
            $comment->uuid()
        );
        $this->repository->update($updatedComment);

        // assert
        $this->assertDatabaseHas('comments', ['uuid' => $comment->uuid(), 'name' => 'My New Name']);
        $this->eventBus->dispatch(Argument::type(CommentWasUpdated::class))->shouldHaveBeenCalled();
    }
}
