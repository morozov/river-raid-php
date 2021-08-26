<?php

declare(strict_types=1);

namespace RiverRaid\Data\TerrainProfile;

enum RenderingMode: int
{
    case SYMMETRICAL = 1;
    case PARALLEL    = 2;

    public function calculateOtherSide(int $c, int $d): int
    {
        return match ($this) {
            self::SYMMETRICAL => 2 * $c - $d,
            self::PARALLEL    => $c + $d,
        };
    }
}
