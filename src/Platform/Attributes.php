<?php

declare(strict_types=1);

namespace RiverRaid\Platform;

use RiverRaid\Image;

final readonly class Attributes
{
    private const int BRIGHTNESS = 197;

    private Color $inkColor;

    private Color $paperColor;

    public function __construct(int $attributes)
    {
        $this->inkColor   = new Color(($attributes >> 0) & 0x07);
        $this->paperColor = new Color(($attributes >> 3) & 0x07);
    }

    public function allocateInkColor(Image $image): int
    {
        return $this->allocateColor($image, $this->inkColor);
    }

    public function allocatePaperColor(Image $image): int
    {
        return $this->allocateColor($image, $this->paperColor);
    }

    private function allocateColor(Image $image, Color $color): int
    {
        return $color->allocate($image, self::BRIGHTNESS);
    }
}
