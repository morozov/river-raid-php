<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Data\Entity;
use RiverRaid\Data\Entity\Property\Orientation;
use RiverRaid\Data\Entity\Type;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Image;
use RiverRaid\Platform\Attributes;

use function sprintf;

abstract class ThreeByOneTileEnemy implements Entity
{
    public function __construct(
        private readonly Type $type,
        private readonly Orientation $orientation,
    ) {
    }

    public function render(
        SpriteRepository $spriteRepository,
        AttributeRepository $attributeRepository,
        Image $image,
        int $x,
        int $y,
    ): void {
        $spriteRepository->getThreeByOneTileEnemySprite($this->orientation, $this->type)->render(
            $image,
            $x,
            $y,
            $this->getAttributes($attributeRepository),
        );
    }

    public function toString(): string
    {
        return sprintf('oriented %s', $this->orientation->toString());
    }

    abstract protected function getAttributes(AttributeRepository $attributeRepository): Attributes;
}
