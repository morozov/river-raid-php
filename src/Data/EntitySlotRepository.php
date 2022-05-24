<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class EntitySlotRepository
{
    /** @param list<EntitySlot> $slots */
    public function __construct(
        private readonly array $slots,
    ) {
    }

    public function getSlot(int $offset): EntitySlot
    {
        return $this->slots[$offset];
    }
}
