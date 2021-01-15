<?php

declare(strict_types=1);

namespace RiverRaid;

final class TerrainLevels
{
    /** @param list<TerrainLevel> $levels */
    public function __construct(
        public array $levels,
    ) {
    }
}
