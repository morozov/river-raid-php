<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Image;

interface Entity
{
    public function render(
        SpriteRepository $spriteRepository,
        AttributeRepository $attributeRepository,
        Image $image,
        int $x,
        int $y
    ): void;

    public function toString(): string;
}
