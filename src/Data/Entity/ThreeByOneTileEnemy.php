<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

use GdImage;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;

use function sprintf;

final class ThreeByOneTileEnemy implements Entity
{
    public function __construct(
        private int $type,
        private int $orientation,
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
                Entity::TYPE_HELICOPTER_REGULAR => 'Regular helicopter',
                Entity::TYPE_SHIP => 'Ship',
                Entity::TYPE_HELICOPTER_ADVANCED => 'Advanced helicopter',
                Entity::TYPE_TANK => 'Tank',
                Entity::TYPE_FIGHTER => 'Fighter',
            },
            $this->orientation === 0 ? 'left' : 'right',
        );
    }
}
