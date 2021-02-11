<?php

declare(strict_types=1);

namespace RiverRaid;

final class IslandRows
{
    /** @param list<IslandRow> $rows */
    public function __construct(
        public array $rows,
    ) {
    }
}
