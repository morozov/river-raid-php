<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class TerrainLevel
{
    /**
     * @param list<TerrainFragment> $fragments
     */
    public function __construct(
        public array $fragments,
    ) {
    }
}
