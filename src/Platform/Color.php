<?php

declare(strict_types=1);

namespace RiverRaid\Platform;

use RiverRaid\Image;

final readonly class Color
{
    /** @var int<0, 1> */
    private int $blue;

    /** @var int<0, 1> */
    private int $red;

    /** @var int<0, 1> */
    private int $green;

    public function __construct(int $color)
    {
        $this->blue  = ($color >> 0) & 0x01;
        $this->red   = ($color >> 1) & 0x01;
        $this->green = ($color >> 2) & 0x01;
    }

    /** @param int<0, 255> $brightness */
    public function allocate(Image $image, int $brightness): int
    {
        return $image->allocateColor(
            $this->red * $brightness,
            $this->green * $brightness,
            $this->blue * $brightness,
        );
    }
}
