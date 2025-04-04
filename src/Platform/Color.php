<?php

declare(strict_types=1);

namespace RiverRaid\Platform;

use RiverRaid\Image;

final class Color
{
    /**
     * @psalm-immutable
     * @var int<0, 1>
     */
    private readonly int $blue;

    /**
     * @psalm-immutable
     * @var int<0, 1>
     */
    private readonly int $red;

    /**
     * @psalm-immutable
     * @var int<0, 1>
     */
    private readonly int $green;

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
