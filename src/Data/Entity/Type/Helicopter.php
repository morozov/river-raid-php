<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Platform\Attributes;

use function sprintf;

abstract class Helicopter extends ThreeByOneTileEnemy
{
    public function toString(): string
    {
        return sprintf('helicopter %s', parent::toString());
    }

    protected function getAttributes(AttributeRepository $attributeRepository): Attributes
    {
        return $attributeRepository->getHelicopterAttributes();
    }
}
