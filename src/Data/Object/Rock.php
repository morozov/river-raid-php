<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use RiverRaid\Data\Entity;

use function sprintf;

/**
 * @psalm-immutable
 */
final class Rock implements Entity
{
    public function __construct(
        public int $type,
    ) {
    }

    public function toString(): string
    {
        return sprintf('Rock #%d', $this->type + 1);
    }
}
