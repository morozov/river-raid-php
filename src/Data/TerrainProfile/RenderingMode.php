<?php

declare(strict_types=1);

namespace RiverRaid\Data\TerrainProfile;

use InvalidArgumentException;

use function sprintf;

final class RenderingMode
{
    private const MODE_SYMMETRICAL = 1;
    private const MODE_PARALLEL    = 2;

    /** @var self::MODE_* */
    private int $mode;

    public function __construct(int $mode)
    {
        $this->mode = match ($mode) {
            self::MODE_SYMMETRICAL,
            self::MODE_PARALLEL,
                => $mode,
            default => throw new InvalidArgumentException(sprintf('Invalid rendering mode %d', $mode)),
        };
    }

    public function calculateOtherSide(int $c, int $d): int
    {
        return match ($this->mode) {
            self::MODE_SYMMETRICAL => 2 * $c - $d,
            self::MODE_PARALLEL    => $c + $d,
        };
    }
}
