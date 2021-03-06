<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

use function sprintf;

final class Rock implements Entity
{
    public function __construct(
        private int $type,
    ) {
    }

    public function render(SpriteRepository $repository, Image $image, int $x, int $y): void
    {
        $repository->getRock($this->type)->render($image, $x, $y);
    }

    public function toString(): string
    {
        return sprintf('Rock #%d', $this->type + 1);
    }
}