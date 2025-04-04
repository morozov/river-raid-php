<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;

final readonly class Level
{
    private const int SIZE_LEVEL_FRAGMENTS = 0x40;
    private const int SIZE_TOTAL_FRAGMENTS = 0x40 * 0x30;

    public function __construct(
        private int $offset,
    ) {
    }

    public function render(
        TerrainFragmentRepository $terrainFragments,
        EntitySlotRepository $entitySlots,
        SpriteRepository $sprites,
        AttributeRepository $attributes,
        Image $image,
    ): void {
        for ($i = 0; $i < self::SIZE_LEVEL_FRAGMENTS; $i++) {
            new LevelFragment(
                $this->rotateFragmentOffset($this->offset * self::SIZE_LEVEL_FRAGMENTS + $i),
            )->render(
                $terrainFragments,
                $entitySlots,
                $sprites,
                $attributes,
                1023 - $i * 16,
                $image,
            );
        }
    }

    private function rotateFragmentOffset(int $offset): int
    {
        return ($offset + 3 + self::SIZE_TOTAL_FRAGMENTS) % self::SIZE_TOTAL_FRAGMENTS;
    }
}
