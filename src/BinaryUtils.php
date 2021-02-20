<?php

declare(strict_types=1);

namespace RiverRaid;

final class BinaryUtils
{
    private function __construct()
    {
    }

    public static function bit(int $byte, int $position): int
    {
        return ($byte >> $position) & 1;
    }
}
