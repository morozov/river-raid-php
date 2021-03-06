<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;

final class Level
{
    private const SIZE_LEVEL_FRAGMENTS = 0x40;
    private const SIZE_TOTAL_FRAGMENTS = 0x40 * 0x30;

    public function __construct(
        private int $offset,
    ) {
    }

    public function render(
        TerrainFragmentRepository $terrainFragments,
        EntitySlotRepository $entitySlots,
        TerrainProfileRepository $terrainProfiles,
        IslandFragmentRepository $islandFragments,
        SpriteRepository $sprites,
        Image $image,
    ): void {
        for ($i = 0; $i < self::SIZE_LEVEL_FRAGMENTS; $i++) {
            (new LevelFragment(
                $this->rotateFragmentOffset($this->offset * self::SIZE_LEVEL_FRAGMENTS + $i)
            ))->render(
                $terrainFragments,
                $entitySlots,
                $terrainProfiles,
                $islandFragments,
                $sprites,
                1023 - $i * 16,
                $image
            );
        }
    }

    private function rotateFragmentOffset(int $offset): int
    {
        return ($offset + 3 + self::SIZE_TOTAL_FRAGMENTS) % self::SIZE_TOTAL_FRAGMENTS;
    }
}
