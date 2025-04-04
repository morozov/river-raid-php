<?php

declare(strict_types=1);

namespace RiverRaid\Data;

final readonly class IslandFragmentRepository
{
    /** @param list<IslandFragment> $fragments */
    public function __construct(
        private array $fragments,
    ) {
    }

    public function getFragment(int $number): IslandFragment
    {
        return $this->fragments[$number - 1];
    }
}
