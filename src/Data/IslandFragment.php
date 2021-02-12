<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class IslandFragment implements Fragment
{
    public function __construct(
        private int $byte1,
        public int $byte2,
        private int $byte3,
    ) {
    }

    public function getProfileNumber(): int
    {
        return $this->byte1;
    }

    public function getRenderingMode(): int
    {
        return $this->byte3;
    }
}
