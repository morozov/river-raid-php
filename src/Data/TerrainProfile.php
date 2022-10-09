<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

interface TerrainProfile
{
    public function renderRiverBanks(
        int $byte2,
        int $byte3,
        RenderingMode $renderingMode,
        int $offset,
        Image $image
    ): void;

    public function renderIsland(
        int $byte2,
        RenderingMode $renderingMode,
        int $offset,
        Image $image
    ): void;
}
