<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Eloquent;

use App\Domain\Comments\CommentStatus;
use App\Domain\Core\CollectionInterface;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\CommentMapper;
use Database\Factories\CommentFactory;
use DateTimeInterface;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Eloquent\CommentMapper
 */
class CommentMapperTest extends TestCase
{
    /** @test */
    public function itConvertsAnEloquentModelToDomainModel(): void
    {
        /** @var CommentEloquentModel $eloquentComment */
        $eloquentComment = CommentFactory::new()->make([
            'created_at' => '2020-05-05 20:36:00',
            'status' => '1',
        ]);

        $mapper = new CommentMapper();
        $comment = $mapper->mapToDomainModel($eloquentComment->toArray());

        $this->assertEquals($eloquentComment->article_uuid, $comment->articleUuid());
        $this->assertEquals($eloquentComment->content, $comment->content());
        $this->assertInstanceOf(DateTimeInterface::class, $comment->createdAt());
        $this->assertEquals($eloquentComment->email, $comment->email());
        $this->assertEquals($eloquentComment->ip, $comment->ip());
        $this->assertEquals($eloquentComment->name, $comment->name());
        $this->assertTrue(CommentStatus::published()->equals($comment->status()));
        $this->assertEquals($eloquentComment->uuid, $comment->uuid());
    }

    /** @test */
    public function itConvertsAnArrayOfEloquentModelsIntoCollectionOfDomainModels(): void
    {
        /** @var CommentEloquentModel $eloquentComment1 */
        $eloquentComment1 = CommentFactory::new()->make([
            'created_at' => '2020-05-05 20:36:00',
            'status' => 1,
        ]);

        /** @var CommentEloquentModel $eloquentComment2 */
        $eloquentComment2 = CommentFactory::new()->make([
            'created_at' => '2020-05-06 20:36:00',
            'status' => 0,
        ]);

        $eloquentCollection = new LaravelCollection([
            $eloquentComment1->toArray(),
            $eloquentComment2->toArray(),
        ]);

        $mapper = new CommentMapper();
        $collection = $mapper->mapToDomainModels($eloquentCollection);

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($eloquentComment1->content, $collection->toArray()[0]->content());
        $this->assertEquals($eloquentComment2->content, $collection->toArray()[1]->content());
    }
}
