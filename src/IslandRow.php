<?php

declare(strict_types=1);

namespace RiverRaid;

final class IslandRow
{
    public function __construct(
        public int $byte1,
        public int $byte2,
        public int $byte3,
    ) {
    }
}
