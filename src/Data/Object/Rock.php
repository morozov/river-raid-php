<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use RiverRaid\Data\Entity;

/**
 * @psalm-immutable
 */
final class Rock implements Entity
{
    public function __construct(
        public int $type,
    ) {
    }
}
