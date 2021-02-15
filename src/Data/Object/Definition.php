<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use LogicException;
use RiverRaid\Data\Entity;

/**
 * @psalm-immutable
 */
final class Definition
{
    private const OBJECT_HELICOPTER_REGULAR  = 1;
    private const OBJECT_SHIP                = 2;
    private const OBJECT_HELICOPTER_ADVANCED = 3;
    private const OBJECT_TANK                = 4;
    private const OBJECT_FIGHTER             = 5;
    private const OBJECT_BALLOON             = 6;
    private const OBJECT_FUEL_STATION        = 7;

    public function __construct(
        private int $byte1,
        private int $byte2,
    ) {
    }

    public function newObject(): ?Entity
    {
        if ($this->byte1 === 0) {
            return null;
        }

        if (($this->byte1 & 0x08) === 0x08) {
            return new Rock($this->byte1 & 0x03);
        }

        $type = $this->byte1 & 0x07;

        return match ($type) {
            self::OBJECT_HELICOPTER_REGULAR,
            self::OBJECT_SHIP,
            self::OBJECT_HELICOPTER_ADVANCED,
            self::OBJECT_TANK,
            self::OBJECT_FIGHTER,
                => new ThreeByOneTileEnemy($type),
            self::OBJECT_BALLOON => new Balloon(),
            self::OBJECT_FUEL_STATION => new FuelStation(),
            default => throw new LogicException(),
        };
    }

    public function getPosition(): int
    {
        return $this->byte2;
    }
}
