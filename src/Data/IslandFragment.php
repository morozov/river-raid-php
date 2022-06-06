<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

final class IslandFragment
{
    public function __construct(
        private readonly TerrainProfile $terrainProfile,
        private readonly int $byte2,
        private readonly RenderingMode $renderingMode,
    ) {
    }

    public function render(int $offset, Image $image): void
    {
        $this->terrainProfile->renderIsland($this->byte2, $this->renderingMode, $offset, $image);
    }
}
