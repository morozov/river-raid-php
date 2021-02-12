<?php

declare(strict_types=1);

namespace RiverRaid\Scene;

/**
 * @psalm-immutable
 */
final class IslandLine
{
    public function __construct(
        public int $left,
        public int $right,
    ) {
    }
}
