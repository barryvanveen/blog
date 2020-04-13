<?php

declare(strict_types=1);

namespace App\Application\View\Admin;

use App\Application\Interfaces\GuardInterface;
use App\Application\Interfaces\UrlGeneratorInterface;
use App\Application\View\PresenterInterface;
use App\Domain\Utils\MetaData;

final class DashboardPresenter implements PresenterInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var GuardInterface */
    private $guard;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        GuardInterface $guard
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->guard = $guard;
    }

    public function present(): array
    {
        return [
            'name' => $this->getUserName(),
            'form_url' => $this->urlGenerator->route('logout.post'),
            'metaData' => $this->buildMetaData(),
        ];
    }

    private function buildMetaData(): MetaData
    {
        return new MetaData(
            'Admin',
            'Admin section',
            $this->urlGenerator->route('admin.dashboard'),
            MetaData::TYPE_WEBSITE
        );
    }

    private function getUserName(): string
    {
        $user = $this->guard->user();

        return $user->name();
    }
}
