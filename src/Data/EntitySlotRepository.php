<?php

declare(strict_types=1);

namespace RiverRaid\Data;

final readonly class EntitySlotRepository
{
    /** @param list<EntitySlot> $slots */
    public function __construct(
        private array $slots,
    ) {
    }

    public function getSlot(int $offset): EntitySlot
    {
        return $this->slots[$offset];
    }
}
