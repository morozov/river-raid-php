<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Platform\Attributes;

use function sprintf;

final class Fighter extends ThreeByOneTileEnemy
{
    public function toString(): string
    {
        return sprintf('fighter %s', parent::toString());
    }

    protected function getAttributes(AttributeRepository $attributeRepository): Attributes
    {
        return $attributeRepository->getFighterAttributes();
    }
}
