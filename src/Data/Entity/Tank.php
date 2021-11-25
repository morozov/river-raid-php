<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

use RiverRaid\Data\Entity;
use RiverRaid\Data\Entity\Tank\Location;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

use function sprintf;

final class Tank implements Entity
{
    public function __construct(
        private Entity $entity,
        private Location $location,
    ) {
    }

    public function render(SpriteRepository $repository, Image $image, int $x, int $y): void
    {
        $this->entity->render($repository, $image, $x, $y);
    }

    public function toString(): string
    {
        return sprintf('%s %s', $this->location->toString(), $this->entity->toString());
    }
}
