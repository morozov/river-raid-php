<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class TerrainFragment implements Fragment
{
    private int $renderingMode;
    private int $islandFragmentNumber;

    public function __construct(
        private int $byte1,
        public int $byte2,
        public int $byte3,
        int $byte4,
    ) {
        $this->renderingMode         = $byte4 & 3;
        $this->islandFragmentNumber = $byte4 >> 2;
    }

    public function getProfileNumber(): int
    {
        return $this->byte1;
    }

    public function getRenderingMode(): int
    {
        return $this->renderingMode;
    }

    public function getIslandFragmentNumber(): int
    {
        return $this->islandFragmentNumber;
    }
}
