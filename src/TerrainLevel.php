<?php

declare(strict_types=1);

namespace RiverRaid;

final class TerrainLevel
{
    /**
     * @param list<TerrainRow> $rows
     */
    public function __construct(
        public array $rows,
    ) {
    }
}
