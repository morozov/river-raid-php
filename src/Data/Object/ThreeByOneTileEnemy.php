<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use GdImage;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;

use function sprintf;

final class ThreeByOneTileEnemy implements Entity
{
    public function __construct(
        public int $type,
        public int $orientation,
    ) {
    }

    public function render(SpriteRepository $repository, GdImage $image, int $x, int $y): void
    {
        $repository->get3By1Enemy($this->orientation, $this->type)->render($image, $x, $y);
    }

    public function toString(): string
    {
        return sprintf(
            '%s headed %s',
            match ($this->type) {
                Definition::OBJECT_HELICOPTER_REGULAR => 'Regular helicopter',
                Definition::OBJECT_SHIP => 'Ship',
                Definition::OBJECT_HELICOPTER_ADVANCED => 'Advanced helicopter',
                Definition::OBJECT_TANK => 'Tank',
                Definition::OBJECT_FIGHTER => 'Fighter',
            },
            $this->orientation === 0 ? 'left' : 'right',
        );
    }
}
