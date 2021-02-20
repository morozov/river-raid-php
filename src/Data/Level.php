<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class Level
{
    /**
     * @param list<TerrainFragment> $terrainFragments
     * @param list<EntitySlot>      $slots
     */
    public function __construct(
        public array $terrainFragments,
        public array $slots,
    ) {
    }
}
