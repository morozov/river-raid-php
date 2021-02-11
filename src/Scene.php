<?php

declare(strict_types=1);

namespace RiverRaid;

final class Scene
{
    /**
     * @param list<int>                     $terrainLeft
     * @param list<int>                     $terrainRight
     * @param array<int,array{0:int,1:int}> $islands
     */
    public function __construct(
        public array $terrainLeft,
        public array $terrainRight,
        public array $islands,
    ) {
    }
}
