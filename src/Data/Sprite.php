<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;
use RiverRaid\Platform\Attributes;

final class Sprite
{
    private const SIZE_TILE = 0x08;

    /** @param list<int> $bytes */
    public function __construct(
        private readonly int $width,
        private readonly array $bytes,
    ) {
    }

    public function render(Image $image, int $x, int $y, Attributes $attributes): void
    {
        $width = self::SIZE_TILE * $this->width;

        $ink   = $attributes->allocateInkColor($image);
        $paper = $attributes->allocatePaperColor($image);

        $px = 0;

        foreach ($this->bytes as $byte) {
            for ($i = 0; $i < self::SIZE_TILE; $i++) {
                if (($byte & 0x80) !== 0) {
                    $color = $ink;

                    if ($image->getPixelColor($px + $x, $y) === $ink) {
                        $color = $paper;
                    }

                    $image->setPixelColor($px + $x, $y, $color);
                }

                $byte <<= 1;
                $px++;
            }

            if ($px < $width) {
                continue;
            }

            $px = 0;
            $y++;
        }
    }
}
