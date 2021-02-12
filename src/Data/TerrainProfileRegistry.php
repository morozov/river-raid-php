<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class TerrainProfileRegistry
{
    /** @param list<TerrainProfile> $profiles */
    public function __construct(
        private array $profiles,
    ) {
    }

    public function getProfile(int $number): TerrainProfile
    {
        return $this->profiles[$number - 1];
    }
}