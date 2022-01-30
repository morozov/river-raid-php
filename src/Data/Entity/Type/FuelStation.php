<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

final class FuelStation implements Entity
{
    public function render(
        SpriteRepository $spriteRepository,
        AttributeRepository $attributeRepository,
        Image $image,
        int $x,
        int $y
    ): void {
        $spriteRepository->getFuelStationSprite()->render(
            $image,
            $x,
            $y,
            $attributeRepository->getFuelStationAttributes()
        );
    }

    public function toString(): string
    {
        return 'fuel station';
    }
}
