<?php

declare(strict_types=1);

namespace RiverRaid\Scene;

/**
 * @psalm-immutable
 */
final class TerrainLine
{
    public function __construct(
        public RiverBankLines $riverBankLines,
        public ?IslandLine $islandLine,
    ) {
    }
}
