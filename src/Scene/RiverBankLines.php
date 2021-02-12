<?php

declare(strict_types=1);

namespace RiverRaid\Scene;

/**
 * @psalm-immutable
 */
final class RiverBankLines
{
    public function __construct(
        public int $left,
        public int $right,
    ) {
    }
}
