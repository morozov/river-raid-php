<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

final class Balloon implements Entity
{
    public function render(SpriteRepository $repository, Image $image, int $x, int $y): void
    {
        $repository->getBalloon()->render($image, $x, $y);
    }

    public function toString(): string
    {
        return 'balloon';
    }
}
