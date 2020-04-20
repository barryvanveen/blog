<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Application\Http\StatusCode;
use Tests\TestCase;

class NotFoundTest extends TestCase
{
    /** @test */
    public function seeErrorPageIfVisitingWrongUrl(): void
    {
        $response = $this->get('/foobar');

        $response->assertStatus(StatusCode::STATUS_NOT_FOUND);
        $response->assertSee('Not Found');
    }
}
