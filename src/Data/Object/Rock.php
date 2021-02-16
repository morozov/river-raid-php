<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use GdImage;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;

use function sprintf;

final class Rock implements Entity
{
    public function __construct(
        public int $type,
    ) {
    }

    public function render(SpriteRepository $repository, GdImage $image, int $x, int $y): void
    {
        $repository->getRock($this->type)->render($image, $x, $y);
    }

    public function toString(): string
    {
        return sprintf('Rock #%d', $this->type + 1);
    }
}
