<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

use RiverRaid\Data\Entity;
use RiverRaid\Data\Entity\ThreeByOneTileEnemy\Orientation;
use RiverRaid\Data\Entity\ThreeByOneTileEnemy\Type;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

use function sprintf;

final class ThreeByOneTileEnemy implements Entity
{
    public function __construct(
        private Type $type,
        private Orientation $orientation,
    ) {
    }

    public function render(SpriteRepository $repository, Image $image, int $x, int $y): void
    {
        $repository->get3By1Enemy($this->orientation, $this->type)->render($image, $x, $y);
    }

    public function toString(): string
    {
        return sprintf('%s oriented %s', $this->type->toString(), $this->orientation->toString());
    }
}
