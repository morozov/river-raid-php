<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class TerrainLevelList
{
    /** @param list<TerrainLevel> $levels */
    public function __construct(
        public array $levels,
    ) {
    }
}
