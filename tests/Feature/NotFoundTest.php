<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundTest extends TestCase
{
    /** @test */
    public function seeErrorPageIfVisitingWrongUrl(): void
    {
        $response = $this->get('/foobar');

        $response->assertStatus(404);
        $response->assertSee('Not Found');
    }
}
