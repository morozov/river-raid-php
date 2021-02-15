<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use RiverRaid\Data\Entity;

/**
 * @psalm-immutable
 */
final class FuelStation implements Entity
{
    public function toString(): string
    {
        return 'Fuel station';
    }
}
