<?php

declare(strict_types=1);

namespace RiverRaid;

use RiverRaid\Scene\TerrainLine;

/**
 * @psalm-immutable
 */
final class Scene
{
    /**
     * @param list<TerrainLine> $terrainLines
     */
    public function __construct(
        public array $terrainLines,
    ) {
    }
}
