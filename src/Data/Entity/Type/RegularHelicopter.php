<?php

declare(strict_types=1);

namespace RiverRaid\Data\Entity\Type;

use Override;
use RiverRaid\Data\Entity\Type\Helicopter as BaseHelicopter;

use function sprintf;

final readonly class RegularHelicopter extends BaseHelicopter
{
    #[Override]
    public function toString(): string
    {
        return sprintf('regular %s', parent::toString());
    }
}
