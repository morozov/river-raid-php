<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

final class TerrainFragment
{
    public function __construct(
        private readonly TerrainProfile $terrainProfile,
        private readonly int $byte2,
        private readonly int $byte3,
        private readonly RenderingMode $renderingMode,
        private readonly ?IslandFragment $islandFragment,
    ) {
    }

    public function render(int $offset, Image $image): void
    {
        $this->terrainProfile->renderRiverBanks($this->byte2, $this->byte3, $this->renderingMode, $offset, $image);

        if ($this->islandFragment === null) {
            return;
        }

        $this->islandFragment->render($offset, $image);
    }
}
