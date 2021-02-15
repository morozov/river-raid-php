<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use RiverRaid\Data\Entity;

use function sprintf;

/**
 * @psalm-immutable
 */
final class ThreeByOneTileEnemy implements Entity
{
    public function __construct(
        public int $type,
        public int $orientation,
    ) {
    }

    public function toString(): string
    {
        return sprintf(
            '%s headed %s',
            match ($this->type) {
                Definition::OBJECT_HELICOPTER_REGULAR => 'Regular helicopter',
                Definition::OBJECT_SHIP => 'Ship',
                Definition::OBJECT_HELICOPTER_ADVANCED => 'Advanced helicopter',
                Definition::OBJECT_TANK => 'Tank',
                Definition::OBJECT_FIGHTER => 'Fighter',
            },
            $this->orientation === 0 ? 'left' : 'right',
        );
    }
}
