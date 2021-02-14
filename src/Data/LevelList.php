<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class LevelList
{
    /** @param list<Level> $levels */
    public function __construct(
        public array $levels,
    ) {
    }
}
