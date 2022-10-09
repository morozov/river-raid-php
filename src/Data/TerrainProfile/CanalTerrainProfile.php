<?php

declare(strict_types=1);

namespace RiverRaid\Data\TerrainProfile;

use RiverRaid\Data\TerrainProfile;
use RiverRaid\Image;

final class CanalTerrainProfile implements TerrainProfile
{
    public function renderRiverBanks(
        int $byte2,
        int $byte3,
        RenderingMode $renderingMode,
        int $offset,
        Image $image
    ): void {
        $color = $image->allocateColor(0, 197, 0);
        $image->drawRectangle(0, $offset, 112, $offset - 15, $color);
        $image->drawRectangle(144, $offset, 255, $offset - 15, $color);
    }

    public function renderIsland(
        int $byte2,
        RenderingMode $renderingMode,
        int $offset,
        Image $image
    ): void {
    }
}
