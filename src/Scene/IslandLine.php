<?php

declare(strict_types=1);

namespace RiverRaid\Scene;

use RiverRaid\Image;

final class IslandLine
{
    public function __construct(
        private int $left,
        private int $right,
    ) {
    }

    public function render(Image $image, int $y, int $ink): void
    {
        $image->drawHorizontalLine($this->left, $this->right, $y, $ink);
    }
}
