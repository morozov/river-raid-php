<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use GdImage;
use RiverRaid\Platform\Attributes;

use function imagecolorat;
use function imagesetpixel;

final class Sprite
{
    private const SIZE_TILE = 0x08;

    /** @param list<int> $bytes */
    public function __construct(
        private int $width,
        private Attributes $attributes,
        private array $bytes,
    ) {
    }

    public function render(GdImage $image, int $x, int $y): void
    {
        $width = self::SIZE_TILE * $this->width;

        $ink   = $this->attributes->allocateInkColor($image);
        $paper = $this->attributes->allocatePaperColor($image);

        $px = 0;

        foreach ($this->bytes as $byte) {
            for ($i = 0; $i < self::SIZE_TILE; $i++) {
                if (($byte & 0x80) !== 0) {
                    $color = $ink;

                    if (imagecolorat($image, $px + $x, $y) === $ink) {
                        $color = $paper;
                    }

                    imagesetpixel($image, $px + $x, $y, $color);
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
