<?php

declare(strict_types=1);

namespace RiverRaid\Data;

final readonly class TerrainFragmentRepository
{
    /** @param list<TerrainFragment> $fragments */
    public function __construct(
        private array $fragments,
    ) {
    }

    public function getFragment(int $offset): TerrainFragment
    {
        return $this->fragments[$offset];
    }
}
