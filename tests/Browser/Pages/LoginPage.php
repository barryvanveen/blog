<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class LoginPage extends Page
{
    public function url()
    {
        return route('login');
    }

    public function elements()
    {
        return [
            '@submit' => 'main form[name="login"] input[type="submit"]',
        ];
    }
}
