<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use RiverRaid\Data\Entity;

/**
 * @psalm-immutable
 */
final class Balloon implements Entity
{
    public function toString(): string
    {
        return 'Balloon';
    }
}
