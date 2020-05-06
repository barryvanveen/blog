<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Controllers;

use App\Application\Http\Controllers\ImagesController;
use App\Application\Interfaces\ImageServerInterface;
use Nyholm\Psr7\ServerRequest;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\TestCase;

/**
 * @covers \App\Application\Http\Controllers\ImagesController
 */
class ImagesControllerTest extends TestCase
{
    /** @var ImagesController */
    private $controller;

    /** @var ObjectProphecy|ImageServerInterface */
    private $imageServer;

    /** @var ServerRequest */
    private $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->imageServer = $this->prophesize(ImageServerInterface::class);
        $this->request = new ServerRequest('GET', '/');

        $this->controller = new ImagesController(
            $this->imageServer->reveal()
        );
    }

    /** @test */
    public function itAppliesTheFitParameterByDefault(): void
    {
        $this->imageServer->outputImage('myFileName', ['fit' => 'max'])
            ->shouldBeCalled();

        $this->controller->show('myFileName', $this->request);
    }

    /** @test */
    public function itAllowsWidthAndHeightParameters(): void
    {
        $this->request = $this->request->withQueryParams([
            'w' => 123,
            'h' => 321,
        ]);

        $this->imageServer->outputImage('myFileName', ['fit' => 'max', 'w' => 123, 'h' => 321])
            ->shouldBeCalled();

        $this->controller->show('myFileName', $this->request);
    }

    /** @test */
    public function itFiltersOutAllOtherParameters(): void
    {
        $this->request = $this->request->withQueryParams([
            'fit' => 'crop',
            'blur' => 5,
        ]);

        $this->imageServer->outputImage('myFileName', ['fit' => 'max'])
            ->shouldBeCalled();

        $this->controller->show('myFileName', $this->request);
    }
}
