<?php

declare(strict_types=1);

namespace RiverRaid;

final class TerrainRow
{
    public function __construct(
        public int $byte1,
        public int $byte2,
        public int $byte3,
        public int $byte4,
    ) {
    }
}
