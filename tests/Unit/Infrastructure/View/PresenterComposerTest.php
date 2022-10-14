<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\View;

use App\Application\View\AssetUrlBuilderInterface;
use App\Application\View\PresenterInterface;
use App\Infrastructure\View\PresenterComposer;
use App\Infrastructure\View\PresenterComposerException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\View;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\View\PresenterComposer
 * @covers \App\Infrastructure\View\PresenterComposerException
 */
class PresenterComposerTest extends TestCase
{
    private ObjectProphecy|Application $application;

    private ObjectProphecy|PresenterComposer $presenterComposer;

    public function setUp(): void
    {
        $this->application = $this->prophesize(Application::class);

        $this->presenterComposer = new PresenterComposer($this->application->reveal());
    }

    /** @test */
    public function itFailsIfTheManifestFileCannotBeFound(): void
    {
        /** @var ObjectProphecy|View $view */
        $view = $this->prophesize(View::class);

        $view->getPath()
            ->willReturn(__DIR__ . '/doesNotExist.blade.php');

        $this->expectException(PresenterComposerException::class);
        $this->expectExceptionMessage('File could not be found:');
        $this->application->make(Argument::type('string'))->shouldNotBeCalled();

        $this->presenterComposer->compose($view->reveal());
    }

    /** @test */
    public function itDoesNothingIfNoModelTagIsFound(): void
    {
        /** @var ObjectProphecy|View $view */
        $view = $this->prophesize(View::class);

        $view->getPath()
            ->willReturn(__DIR__ . '/emptyView.blade.php');

        $this->application->make(Argument::type('string'))->shouldNotBeCalled();

        $this->presenterComposer->compose($view->reveal());
    }

    /** @test */
    public function itThrowsAnErrorIfAnInvalidPresenterIsGiven(): void
    {
        $noPresenter = $this->prophesize(AssetUrlBuilderInterface::class);

        $this->application
            ->make(Argument::exact('Acme\MyPresenter'))
            ->willReturn($noPresenter->reveal());

        /** @var ObjectProphecy|View $view */
        $view = $this->prophesize(View::class);

        $view->getPath()
            ->willReturn(__DIR__ . '/view.blade.php');

        $this->expectException(PresenterComposerException::class);
        $this->expectExceptionMessage('Presenter should implement correct interface:');

        $this->presenterComposer->compose($view->reveal());
    }

    /** @test */
    public function itAssignsThePresenterOutputToTheView(): void
    {
        /** @var ObjectProphecy|PresenterInterface $presenter */
        $presenter = $this->prophesize(PresenterInterface::class);

        $presenter->present()
            ->willReturn([
                'foo' => 'bar',
                'baz' => 123,
            ]);

        $this->application
            ->make(Argument::exact('Acme\MyPresenter'))
            ->willReturn($presenter->reveal());

        /** @var ObjectProphecy|View $view */
        $view = $this->prophesize(View::class);

        $view->getPath()
            ->willReturn(__DIR__ . '/view.blade.php');

        $view->with(Argument::type('array'))->shouldBeCalled();

        $this->presenterComposer->compose($view->reveal());
    }
}
