<?php

declare(strict_types=1);

namespace RiverRaid;

final class TerrainProfiles
{
    /** @param list<TerrainProfile> $profiles */
    public function __construct(
        public array $profiles,
    ) {
    }
}
