<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Property;

use RiverRaid\Data\AttributeRepository;
use RiverRaid\Platform\Attributes;

enum Location: int
{
    case BRIDGE = 0;
    case BANK   = 1;

    public function getTankAttributes(AttributeRepository $attributeRepository): Attributes
    {
        return match ($this) {
            self::BRIDGE => $attributeRepository->getTankOnBridgeAttributes(),
            self::BANK   => $attributeRepository->getTankOnBankAttributes(),
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::BRIDGE => 'bridge',
            self::BANK   => 'bank',
        };
    }
}
