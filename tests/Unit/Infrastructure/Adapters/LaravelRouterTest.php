<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelRouter;
use Illuminate\Routing\Router;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelRouter
 */
class LaravelRouterTest extends TestCase
{
    private ObjectProphecy|Router $laravelRouter;

    public function setUp(): void
    {
        $this->laravelRouter = $this->prophesize(Router::class);
    }

    /**
     * @test
     *
     * @dataProvider routeNameDataProvider
     */
    public function itReturnsWhetherTheCurrentRouteIsAnAdminRoute(
        ?string $currentRoute,
        bool $expectedResult,
    ): void {
        $this->laravelRouter->currentRouteName()
            ->willReturn($currentRoute)
            ->shouldBeCalled();

        $router = new LaravelRouter(
            $this->laravelRouter->reveal()
        );

        $this->assertEquals($expectedResult, $router->currentRouteIsAdminRoute());
    }

    public function routeNameDataProvider()
    {
        return [
            [
                'home',
                false,
            ],
            [
                'articles.index',
                false,
            ],
            [
                'admin.dashboard',
                true,
            ],
            [
                'admin.articles.index',
                true,
            ],
            [
                null,
                false,
            ],
        ];
    }
}
