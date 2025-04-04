<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use Override;
use RiverRaid\Data\AttributeRepository;
use RiverRaid\Data\Entity;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;

use function sprintf;

final class Rock implements Entity
{
    public function __construct(
        private readonly int $type,
    ) {
    }

    #[Override]
    public function render(
        SpriteRepository $spriteRepository,
        AttributeRepository $attributeRepository,
        Image $image,
        int $x,
        int $y,
    ): void {
        $spriteRepository->getRockSprite($this->type)->render(
            $image,
            $x,
            $y,
            $attributeRepository->getRockAttributes(),
        );
    }

    #[Override]
    public function toString(): string
    {
        return sprintf('rock #%d', $this->type + 1);
    }
}
