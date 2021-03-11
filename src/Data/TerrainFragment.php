<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Image;

final class TerrainFragment
{
    private RenderingMode $renderingMode;

    private int $islandFragmentNumber;

    public function __construct(
        private int $byte1,
        private int $byte2,
        private int $byte3,
        int $byte4,
    ) {
        $this->renderingMode         = new RenderingMode($byte4 & 3);
        $this->islandFragmentNumber = $byte4 >> 2;
    }

    public function render(
        TerrainProfileRepository $terrainProfiles,
        IslandFragmentRepository $islandFragments,
        int $offset,
        Image $image
    ): void {
        $terrainProfiles->getProfile($this->byte1)
            ->renderRiverBanks($this->byte2, $this->byte3, $this->renderingMode, $offset, $image);

        if ($this->islandFragmentNumber <= 0) {
            return;
        }

        $islandFragments->getFragment($this->islandFragmentNumber)
            ->render($terrainProfiles, $offset, $image);
    }
}
