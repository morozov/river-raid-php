<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;

interface Entity
{
    public const TYPE_SHIP                = 2;
    public const TYPE_TANK                = 4;
    public const TYPE_FIGHTER             = 5;
    public const TYPE_BALLOON             = 6;
    public const TYPE_FUEL_STATION        = 7;

    public function toString(): string;

    public function render(SpriteRepository $repository, Image $image, int $x, int $y): void;
}
