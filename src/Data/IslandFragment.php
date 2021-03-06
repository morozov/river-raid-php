<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;

final class IslandFragment
{
    public function __construct(
        private int $byte1,
        private int $byte2,
        private int $byte3,
    ) {
    }

    public function render(TerrainProfileRepository $terrainProfiles, int $offset, Image $image): void
    {
        $terrainProfiles->getProfile($this->byte1)
            ->renderIsland($this->byte2, $this->byte3, $offset, $image);
    }
}
