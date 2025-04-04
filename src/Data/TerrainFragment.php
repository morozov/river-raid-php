<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

final readonly class TerrainFragment
{
    public function __construct(
        private TerrainProfile $terrainProfile,
        private int $byte2,
        private int $byte3,
        private RenderingMode $renderingMode,
        private ?IslandFragment $islandFragment,
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
