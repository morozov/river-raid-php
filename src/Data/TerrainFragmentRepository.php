<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class TerrainFragmentRepository
{
    /** @param list<TerrainFragment> $fragments */
    public function __construct(
        private readonly array $fragments,
    ) {
    }

    public function getFragment(int $offset): TerrainFragment
    {
        return $this->fragments[$offset];
    }
}
