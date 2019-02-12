<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class HomepageTest extends DuskTestCase
{
    public function testHomepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage())
                    ->assertSeeIn('@home-header', 'Homepage - Barry van Veen');
        });
    }
}
