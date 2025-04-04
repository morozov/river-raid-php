<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use Override;
use RiverRaid\Data\AttributeRepository;
use RiverRaid\Platform\Attributes;

use function sprintf;

abstract class Helicopter extends ThreeByOneTileEnemy
{
    #[Override]
    public function toString(): string
    {
        return sprintf('helicopter %s', parent::toString());
    }

    #[Override]
    protected function getAttributes(AttributeRepository $attributeRepository): Attributes
    {
        return $attributeRepository->getHelicopterAttributes();
    }
}
