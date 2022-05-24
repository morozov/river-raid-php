<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Data\Entity\Property\Location;
use RiverRaid\Data\Entity\Property\Orientation;
use RiverRaid\Data\Entity\Type;
use RiverRaid\Platform\Attributes;

use function sprintf;

final class Tank extends ThreeByOneTileEnemy
{
    public function __construct(
        Type $type,
        Orientation $orientation,
        private readonly Location $location,
    ) {
        parent::__construct($type, $orientation);
    }

    public function toString(): string
    {
        return sprintf('tank on %s %s', $this->location->toString(), parent::toString());
    }

    protected function getAttributes(AttributeRepository $attributeRepository): Attributes
    {
        return $this->location->getTankAttributes($attributeRepository);
    }
}
