<?php

declare(strict_types=1);

namespace RiverRaid\Platform;

use GdImage;

final class Attributes
{
    private const BRIGHTNESS = 197;

    /** @psalm-immutable */
    private Color $inkColor;

    /** @psalm-immutable */
    private Color $paperColor;

    public function __construct(int $attributes)
    {
        $this->inkColor   = new Color(($attributes >> 0) & 0x07);
        $this->paperColor = new Color(($attributes >> 3) & 0x07);
    }

    public function allocateInkColor(GdImage $image): int
    {
        return $this->allocateColor($image, $this->inkColor);
    }

    public function allocatePaperColor(GdImage $image): int
    {
        return $this->allocateColor($image, $this->paperColor);
    }

    private function allocateColor(GdImage $image, Color $color): int
    {
        return $color->allocate($image, self::BRIGHTNESS);
    }
}
