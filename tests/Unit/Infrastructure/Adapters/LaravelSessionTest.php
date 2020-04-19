<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Adapters;

use App\Infrastructure\Adapters\LaravelSession;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

/**
 * @covers \App\Infrastructure\Adapters\LaravelSession
 */
class LaravelSessionTest extends TestCase
{
    /** @var Session */
    private $laravelSession;

    public function setUp(): void
    {
        parent::setUp();

        $this->laravelSession = $this->app->make(Session::class);
    }

    /** @test */
    public function itReturnsNullWhenNoPreviousUrlIsSet(): void
    {
        $session = new LaravelSession($this->laravelSession);

        $this->assertEquals(null, $session->previousUrl());
    }

    /** @test */
    public function itReturnsThePreviousUrl(): void
    {
        $this->laravelSession->setPreviousUrl('http://myurl');

        $session = new LaravelSession($this->laravelSession);

        $this->assertEquals('http://myurl', $session->previousUrl());
    }

    /** @test */
    public function itReturnsNullWhenNoIntendedUrlIsSet(): void
    {
        $session = new LaravelSession($this->laravelSession);

        $this->assertEquals(null, $session->intendedUrl());
    }

    /** @test */
    public function itReturnsTheIntendedUrl(): void
    {
        $this->laravelSession->put('url.intended', 'http://foo/url');

        $session = new LaravelSession($this->laravelSession);

        $this->assertEquals('http://foo/url', $session->intendedUrl());
    }

    /** @test */
    public function itFlashesNewErrorMessages(): void
    {
        $session = new LaravelSession($this->laravelSession);

        $session->flashErrors([
            'mykey1' => 'myerror1',
        ]);

        /** @var ViewErrorBag|null $errors */
        $errors = $this->laravelSession->get('errors');

        $this->assertInstanceOf(ViewErrorBag::class, $errors);
        $this->assertCount(1, $errors);
        $this->assertEquals('myerror1', $errors->get('mykey1')[0]);
    }

    /** @test */
    public function itOverwritesInvalidExistingErrors(): void
    {
        $this->laravelSession->put('errors', 123);

        $session = new LaravelSession($this->laravelSession);

        $session->flashErrors([
            'newkey' => 'newvalue',
        ]);

        /** @var ViewErrorBag|null $errors */
        $errors = $this->laravelSession->get('errors');

        $this->assertInstanceOf(ViewErrorBag::class, $errors);
        $this->assertCount(1, $errors);
        $this->assertEquals('newvalue', $errors->get('newkey')[0]);
    }

    /** @test */
    public function itOverwritesOldErrorMessages(): void
    {
        $oldErrors = new ViewErrorBag();
        $oldErrors->put('default', new MessageBag(['oldkey' => 'oldvalue']));

        $this->laravelSession->put('errors', $oldErrors);

        $session = new LaravelSession($this->laravelSession);

        $session->flashErrors([
            'newkey' => 'newvalue',
        ]);

        /** @var ViewErrorBag|null $errors */
        $errors = $this->laravelSession->get('errors');

        $this->assertInstanceOf(ViewErrorBag::class, $errors);
        $this->assertCount(1, $errors);
        $this->assertEquals('newvalue', $errors->get('newkey')[0]);
    }

    /** @test */
    public function itGetsOldInputs(): void
    {
        $this->laravelSession->put('_old_input', [
            'name' => 'oldName',
        ]);

        $session = new LaravelSession($this->laravelSession);

        $this->assertEquals('oldName', $session->oldInput('name'));
        $this->assertEquals(null, $session->oldInput('foo'));
        $this->assertEquals('bar', $session->oldInput('foo', 'bar'));
    }
}
