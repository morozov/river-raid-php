<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use Override;
use RiverRaid\Data\AttributeRepository;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

final readonly class Balloon implements Entity
{
    #[Override]
    public function render(
        SpriteRepository $spriteRepository,
        AttributeRepository $attributeRepository,
        Image $image,
        int $x,
        int $y,
    ): void {
        $spriteRepository->getBalloonSprite()->render(
            $image,
            $x,
            $y,
            $attributeRepository->getBalloonAttributes(),
        );
    }

    #[Override]
    public function toString(): string
    {
        return 'balloon';
    }
}
