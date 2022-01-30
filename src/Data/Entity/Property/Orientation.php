<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Property;

enum Orientation: int
{
    case LEFT  = 0;
    case RIGHT = 1;

    public function toString(): string
    {
        return match ($this) {
            self::LEFT  => 'left',
            self::RIGHT => 'right',
        };
    }
}
