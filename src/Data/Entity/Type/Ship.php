<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Platform\Attributes;

use function sprintf;

final class Ship extends ThreeByOneTileEnemy
{
    public function toString(): string
    {
        return sprintf('ship %s', parent::toString());
    }

    protected function getAttributes(AttributeRepository $attributeRepository): Attributes
    {
        return $attributeRepository->getShipAttributes();
    }
}
