<?php

declare(strict_types=1);

namespace RiverRaid\Data\Object;

use GdImage;
use LogicException;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;

final class Definition
{
    public const OBJECT_HELICOPTER_REGULAR  = 1;
    public const OBJECT_SHIP                = 2;
    public const OBJECT_HELICOPTER_ADVANCED = 3;
    public const OBJECT_TANK                = 4;
    public const OBJECT_FIGHTER             = 5;
    public const OBJECT_BALLOON             = 6;
    public const OBJECT_FUEL_STATION        = 7;

    public function __construct(
        private int $byte1,
        private int $byte2,
    ) {
    }

    public function render(SpriteRepository $sprites, GdImage $image, int $y): void
    {
        $object = $this->newObject();

        if ($object === null) {
            return;
        }

        $object->render($sprites, $image, $this->byte2, $y);
    }

    private function newObject(): ?Entity
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
            => new ThreeByOneTileEnemy(
                $type,
                ($this->byte1 >> 6) & 0x01
            ),
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
