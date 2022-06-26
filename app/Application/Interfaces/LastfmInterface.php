<?php

declare(strict_types=1);

namespace App\Application\Interfaces;

use App\Domain\Music\Album;

interface LastfmInterface
{
    /** @return Album[] */
    public function topAlbumsForLastMonth(): array;
}
