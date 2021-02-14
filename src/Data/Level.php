<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\Object\Definition;

/**
 * @psalm-immutable
 */
final class Level
{
    /**
     * @param list<TerrainFragment> $terrainFragments
     * @param list<Definition>      $objectDefinitions
     */
    public function __construct(
        public array $terrainFragments,
        public array $objectDefinitions,
    ) {
    }
}
