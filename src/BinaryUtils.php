<?php

declare(strict_types=1);

namespace RiverRaid;

final class BinaryUtils
{
    public static function bit(int $byte, int $position): int
    {
        return ($byte >> $position) & 1;
    }
}
