<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use RiverRaid\Data\Entity\Type\Helicopter as BaseHelicopter;

use function sprintf;

final class RegularHelicopter extends BaseHelicopter
{
    public function toString(): string
    {
        return sprintf('regular %s', parent::toString());
    }
}
