<?php

declare(strict_types=1);

namespace RiverRaid\Data\TerrainProfile;

use RiverRaid\Data\TerrainProfile;
use RiverRaid\Image;

final class RoadAndBridgeTerrainProfile implements TerrainProfile
{
    public function renderRiverBanks(
        int $byte2,
        int $byte3,
        RenderingMode $renderingMode,
        int $offset,
        Image $image,
    ): void {
        $roadColor   = $image->allocateColor(197, 197, 197);
        $bridgeColor = $image->allocateColor(197, 197, 0);
        $image->drawRectangle(0, $offset, 112, $offset - 15, $roadColor);
        $image->drawRectangle(113, $offset, 143, $offset - 15, $bridgeColor);
        $image->drawRectangle(144, $offset, 255, $offset - 15, $roadColor);
    }

    public function renderIsland(
        int $byte2,
        RenderingMode $renderingMode,
        int $offset,
        Image $image,
    ): void {
    }
}
