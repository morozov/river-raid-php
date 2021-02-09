<?php

declare(strict_types=1);

namespace RiverRaid;

final class Scene
{
    /**
     * @param list<int> $terrainLeft
     * @param list<int> $terrainRight
     */
    public function __construct(
        public array $terrainLeft,
        public array $terrainRight,
    ) {
    }
}
