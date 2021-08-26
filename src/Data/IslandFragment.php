<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

final class IslandFragment
{
    private RenderingMode $renderingMode;

    public function __construct(
        private int $byte1,
        private int $byte2,
        int $byte3,
    ) {
        $this->renderingMode = RenderingMode::from($byte3);
    }

    public function render(TerrainProfileRepository $terrainProfiles, int $offset, Image $image): void
    {
        $terrainProfiles->getProfile($this->byte1)
            ->renderIsland($this->byte2, $this->renderingMode, $offset, $image);
    }
}
