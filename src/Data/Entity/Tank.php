<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity;

use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

use function sprintf;

final class Tank implements Entity
{
    private const LOCATION_BRIDGE     = 0;
    private const LOCATION_RIVER_BANK = 1;

    public function __construct(
        private Entity $entity,
        private int $location,
    ) {
    }

    public function render(SpriteRepository $repository, Image $image, int $x, int $y): void
    {
        $this->entity->render($repository, $image, $x, $y);
    }

    public function toString(): string
    {
        return sprintf('%s %s', match ($this->location) {
            self::LOCATION_RIVER_BANK => 'river bank',
            self::LOCATION_BRIDGE => 'bridge',
        }, $this->entity->toString());
    }
}
