<?php

declare(strict_types=1);

namespace RiverRaid\Scene;

use RiverRaid\Image;

final class RiverBankLines
{
    public function __construct(
        private int $left,
        private int $right,
    ) {
    }

    public function render(Image $image, int $y, int $ink): void
    {
        $image->drawHorizontalLine(0, $this->left, $y, $ink);
        $image->drawHorizontalLine($this->right, 255, $y, $ink);
    }
}
