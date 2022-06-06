<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;

final class LevelFragment
{
    public function __construct(
        private readonly int $offset,
    ) {
    }

    public function render(
        TerrainFragmentRepository $terrainFragments,
        EntitySlotRepository $entitySlots,
        SpriteRepository $sprites,
        AttributeRepository $attributes,
        int $y,
        Image $image,
    ): void {
        $terrainFragments->getFragment($this->offset)
            ->render($y, $image);

        for ($i = 0; $i < 2; $i++) {
            $entitySlots->getSlot(
                $this->offset * 2 + $i
            )->render($sprites, $attributes, $y - $i * 8, $image);
        }
    }
}
