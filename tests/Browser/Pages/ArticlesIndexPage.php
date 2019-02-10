<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class ArticlesIndexPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return route('blog.index');
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     */
    public function assert(Browser $browser)
    {
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@title' => '#title',
        ];
    }
}
