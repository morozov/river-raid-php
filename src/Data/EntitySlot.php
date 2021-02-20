<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use LogicException;
use RiverRaid\BinaryUtils;
use RiverRaid\Data\Entity\Balloon;
use RiverRaid\Data\Entity\FuelStation;
use RiverRaid\Data\Entity\Rock;
use RiverRaid\Data\Entity\ThreeByOneTileEnemy;
use RiverRaid\Image;

/**
 * Entity slot contains an entity definition and position. A slot can be empty.
 */
final class EntitySlot
{
    /**
     * Bit that defines whether the entity is a rock or an interactive entity.
     */
    private const BIT_ROCK = 3;

    /**
     * Bit that defines the entity orientation (left or right), if applicable.
     */
    private const BIT_ORIENTATION = 6;

    /**
     * Bits that define the rock type.
     */
    private const BITS_ROCK_TYPE = 0x03;

    /**
     * Bits that define the interactive entity type.
     */
    private const BITS_INTERACTIVE_TYPE = 0x07;

    private ?Entity $entity;

    public function __construct(
        int $definition,
        private int $position,
    ) {
        $this->entity = $this->newEntity($definition);
    }

    public function render(SpriteRepository $sprites, Image $image, int $y): void
    {
        if ($this->entity === null) {
            return;
        }

        $this->entity->render($sprites, $image, $this->position, $y);
    }

    private function newEntity(int $definition): ?Entity
    {
        if ($definition === 0) {
            return null;
        }

        if (BinaryUtils::bit($definition, self::BIT_ROCK) === 1) {
            return new Rock($definition & self::BITS_ROCK_TYPE);
        }

        $type = $definition & self::BITS_INTERACTIVE_TYPE;

        return match ($type) {
            Entity::TYPE_HELICOPTER_REGULAR,
            Entity::TYPE_SHIP,
            Entity::TYPE_HELICOPTER_ADVANCED,
            Entity::TYPE_TANK,
            Entity::TYPE_FIGHTER,
            => new ThreeByOneTileEnemy(
                $type,
                BinaryUtils::bit($definition, self::BIT_ORIENTATION)
            ),
            Entity::TYPE_BALLOON => new Balloon(),
            Entity::TYPE_FUEL_STATION => new FuelStation(),
            default => throw new LogicException(),
        };
    }
}
