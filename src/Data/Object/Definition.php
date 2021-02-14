<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

/**
 * @psalm-immutable
 */
final class Definition
{
    public function __construct(
        public int $byte1,
        public int $byte2,
    ) {
    }
}
