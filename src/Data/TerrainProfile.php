<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use LogicException;
use RiverRaid\Image;

final class TerrainProfile
{
    private const RENDERING_MODE_SYMMETRICAL = 1;
    private const RENDERING_MODE_PARALLEL    = 2;

    /**
     * @param list<int> $values
     */
    public function __construct(
        private array $values,
    ) {
    }

    public function renderRiverBanks(
        int $byte2,
        int $byte3,
        int $renderingMode,
        int $offset,
        Image $image
    ): void {
        $ink = $image->allocateColor(0, 197, 0);

        foreach ($this->values as $line => $value) {
            $coordinateLeft = $byte3 + $value;

            $left  = $coordinateLeft - 6;
            $right = $this->calcOtherSide($byte2, $coordinateLeft, $renderingMode);

            $image->drawHorizontalLine(0, $left, $offset - $line, $ink);
            $image->drawHorizontalLine($right, 255, $offset - $line, $ink);
        }
    }

    public function renderIsland(
        int $byte2,
        int $renderingMode,
        int $offset,
        Image $image
    ): void {
        $ink = $image->allocateColor(0, 197, 0);

        foreach ($this->values as $line => $value) {
            $side1 = 0x80 + $byte2 + $value;
            $side2 = $this->calcOtherSide(
                0x3C,
                $byte2 + $value,
                $renderingMode,
            );

            $image->drawHorizontalLine($side2, $side1 + 10, $offset - $line, $ink);
        }
    }

    private function calcOtherSide(int $c, int $d, int $mode): int
    {
        if ($mode === self::RENDERING_MODE_SYMMETRICAL) {
            return 2 * $c - $d;
        }

        if ($mode === self::RENDERING_MODE_PARALLEL) {
            return $c + $d;
        }

        throw new LogicException();
    }
}
