<?php

declare(strict_types=1);

namespace RiverRaid\Scene;

use RiverRaid\Image;

final class TerrainLine
{
    public function __construct(
        private RiverBankLines $riverBankLines,
        private ?IslandLine $islandLine,
    ) {
    }

    public function render(Image $image, int $y, int $ink): void
    {
        $this->riverBankLines->render($image, $y, $ink);

        if ($this->islandLine === null) {
            return;
        }

        $this->islandLine->render($image, $y, $ink);
    }
}
