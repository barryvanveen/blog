<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

class AdminDashboardPage extends Page
{
    public function url()
    {
        return route('admin.dashboard');
    }

    public function elements()
    {
        return [
            '@logoutButton' => 'form[name=logout] input[name=submit]',
        ];
    }
}
