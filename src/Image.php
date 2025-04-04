<?php

declare(strict_types=1);

namespace RiverRaid;

use GdImage;
use RuntimeException;

use function imagecolorallocate;
use function imagecolorat;
use function imagecreatetruecolor;
use function imagedestroy;
use function imagefill;
use function imagefilledrectangle;
use function imageline;
use function imagepng;
use function imagesetpixel;

final class Image
{
    private readonly GdImage $image;

    /**
     * @param positive-int $width
     * @param positive-int $height
     */
    public function __construct(int $width, int $height)
    {
        $image = imagecreatetruecolor($width, $height);

        if ($image === false) {
            throw new RuntimeException();
        }

        $this->image = $image;

        $paper = $this->allocateColor(0, 0, 197);
        imagefill($this->image, 0, 0, $paper);
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }

    /**
     * @param int<0, 255> $red
     * @param int<0, 255> $green
     * @param int<0, 255> $blue
     */
    public function allocateColor(int $red, int $green, int $blue): int
    {
        $color = imagecolorallocate($this->image, $red, $green, $blue);

        if ($color === false) {
            throw new RuntimeException();
        }

        return $color;
    }

    public function getPixelColor(int $x, int $y): int
    {
        $color = imagecolorat($this->image, $x, $y);

        if ($color === false) {
            throw new RuntimeException();
        }

        return $color;
    }

    public function setPixelColor(int $x, int $y, int $color): void
    {
        imagesetpixel($this->image, $x, $y, $color);
    }

    public function drawHorizontalLine(int $x1, int $x2, int $y, int $color): void
    {
        imageline($this->image, $x1, $y, $x2, $y, $color);
    }

    public function drawRectangle(int $x1, int $y1, int $x2, int $y2, int $color): void
    {
        imagefilledrectangle($this->image, $x1, $y1, $x2, $y2, $color);
    }

    public function save(string $path): void
    {
        imagepng($this->image, $path);
    }
}
