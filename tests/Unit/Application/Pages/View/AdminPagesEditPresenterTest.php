<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Pages\View;

use App\Application\Interfaces\SessionInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\Pages\View\AdminPagesEditPresenter;
use App\Domain\Pages\PageRepositoryInterface;
use App\Domain\Pages\Requests\AdminPageEditRequestInterface;
use App\Infrastructure\Eloquent\PageEloquentModel;
use App\Infrastructure\Eloquent\PageMapper;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Pages\View\AdminPagesEditPresenter
 */
class AdminPagesEditPresenterTest extends TestCase
{
    /** @test */
    public function itPresentsTheCorrectValues(): void
    {
        /** @var PageEloquentModel $page */
        $page = factory(PageEloquentModel::class)->make();

        $mapper = new PageMapper();

        /** @var ObjectProphecy|UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->route(Argument::cetera())->willReturn('http://myurl');

        /** @var ObjectProphecy|PageRepositoryInterface $repository */
        $repository = $this->prophesize(PageRepositoryInterface::class);
        $repository->getBySlug(Argument::exact($page->slug))->willReturn(
            $mapper->mapToDomainModel($page->toArray())
        );

        /** @var ObjectProphecy|AdminPageEditRequestInterface $request */
        $request = $this->prophesize(AdminPageEditRequestInterface::class);
        $request->slug()->willReturn($page->slug);

        /** @var ObjectProphecy|SessionInterface $session */
        $session = $this->prophesize(SessionInterface::class);
        $session->oldInput('title', Argument::cetera())->willReturn('old title');
        $session->oldInput(Argument::cetera())->willReturnArgument(1);

        $presenter = new AdminPagesEditPresenter(
            $urlGenerator->reveal(),
            $session->reveal(),
            $repository->reveal(),
            $request->reveal()
        );

        $this->assertEquals([
            'title' => 'Edit page',
            'update_url' => 'http://myurl',
            'page' => [
                'title' => 'old title',
                'slug' => $page->slug,
                'description' => $page->description,
                'content' => $page->content,
            ],
        ], $presenter->present());
    }
}
