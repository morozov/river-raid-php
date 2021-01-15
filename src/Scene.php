<?php

declare(strict_types=1);

namespace RiverRaid;

final class Scene
{
    /** @param list<int> $terrainLeft */
    public function __construct(
        public array $terrainLeft,
    ) {
    }
}
