<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use GdImage;

interface Entity
{
    public const TYPE_HELICOPTER_REGULAR  = 1;
    public const TYPE_SHIP                = 2;
    public const TYPE_HELICOPTER_ADVANCED = 3;
    public const TYPE_TANK                = 4;
    public const TYPE_FIGHTER             = 5;
    public const TYPE_BALLOON             = 6;
    public const TYPE_FUEL_STATION        = 7;

    public function toString(): string;

    public function render(SpriteRepository $repository, GdImage $image, int $x, int $y): void;
}
