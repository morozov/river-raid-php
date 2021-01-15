<?php

declare(strict_types=1);

namespace RiverRaid;

final class TerrainProfile
{
    /**
     * @param list<int> $values
     */
    public function __construct(
        public array $values,
    ) {
    }
}
