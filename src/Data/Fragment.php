<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
interface Fragment
{
    public function getProfileNumber(): int;

    public function getRenderingMode(): int;
}
