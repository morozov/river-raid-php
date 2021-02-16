<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use GdImage;

interface Entity
{
    public function toString(): string;

    public function render(SpriteRepository $repository, GdImage $image, int $x, int $y): void;
}
