<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

final class TerrainProfile
{
    /**
     * @param list<int> $values
     */
    public function __construct(
        private array $values,
    ) {
    }

    public function renderRiverBanks(
        int $byte2,
        int $byte3,
        RenderingMode $renderingMode,
        int $offset,
        Image $image
    ): void {
        $ink = $image->allocateColor(0, 197, 0);

        foreach ($this->values as $line => $value) {
            $coordinateLeft = $byte3 + $value;

            $left  = $coordinateLeft - 6;
            $right = $renderingMode->calculateOtherSide($byte2, $coordinateLeft);

            $image->drawHorizontalLine(0, $left, $offset - $line, $ink);
            $image->drawHorizontalLine($right, 255, $offset - $line, $ink);
        }
    }

    public function renderIsland(
        int $byte2,
        RenderingMode $renderingMode,
        int $offset,
        Image $image
    ): void {
        $ink = $image->allocateColor(0, 197, 0);

        foreach ($this->values as $line => $value) {
            $side1 = 0x80 + $byte2 + $value;
            $side2 = $renderingMode->calculateOtherSide(0x3C, $byte2 + $value);

            $image->drawHorizontalLine($side2, $side1 + 10, $offset - $line, $ink);
        }
    }
}
