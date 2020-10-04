<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Comments\View;

use App\Application\Comments\View\AdminCommentsIndexPresenter;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Domain\Comments\CommentRepositoryInterface;
use App\Domain\Comments\CommentStatus;
use App\Infrastructure\Adapters\LaravelCollection;
use App\Infrastructure\Eloquent\CommentEloquentModel;
use App\Infrastructure\Eloquent\CommentMapper;
use Database\Factories\CommentFactory;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Comments\View\AdminCommentsIndexPresenter
 */
class AdminCommentsIndexPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var CommentEloquentModel $comment1 */
        $comment1 = CommentFactory::new()->make([
            'created_at' => '2020-01-17 17:03:05',
            'status' => (string) CommentStatus::published(),
        ]);

        /** @var CommentEloquentModel $comment2 */
        $comment2 = CommentFactory::new()->make([
            'created_at' => '2020-02-18 17:03:05',
            'status' => (string) CommentStatus::unpublished(),
        ]);

        $mapper = new CommentMapper();

        $collection = $mapper->mapToDomainModels(
            new LaravelCollection([
                $comment1->toArray(),
                $comment2->toArray(),
            ])
        );

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::exact('admin.comments.create'))->willReturn('http://newurl');
        $urlGenerator->route(Argument::exact('admin.comments.edit'), Argument::type('array'))->willReturn('http://editurl');

        /** @var ObjectProphecy|CommentRepositoryInterface $repository */
        $repository = $this->prophesize(CommentRepositoryInterface::class);
        $repository->allOrdered()->willReturn($collection);

        $presenter = new AdminCommentsIndexPresenter(
            $repository->reveal(),
            $urlGenerator->reveal()
        );

        $this->assertEquals([
            'title' => 'Comments',
            'comments' => [
                [
                    'is_online' => true,
                    'uuid' => $comment1->uuid,
                    'name' => $comment1->name,
                    'content' => substr($comment1->content, 0, 100),
                    'created_at' => 'Jan 17, 2020',
                    'edit_url' => 'http://editurl',
                ],
                [
                    'is_online' => false,
                    'uuid' => $comment2->uuid,
                    'name' => $comment2->name,
                    'content' => substr($comment2->content, 0, 100),
                    'created_at' => 'Feb 18, 2020',
                    'edit_url' => 'http://editurl',
                ],
            ],
            'create_url' => 'http://newurl',
        ], $presenter->present());
    }
}
