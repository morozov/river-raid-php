<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\BinaryUtils;
use RiverRaid\Data\Entity\Property\Location;
use RiverRaid\Data\Entity\Property\Orientation;
use RiverRaid\Data\Entity\Type;
use RiverRaid\Data\Entity\Type\AdvancedHelicopter;
use RiverRaid\Data\Entity\Type\Balloon;
use RiverRaid\Data\Entity\Type\Fighter;
use RiverRaid\Data\Entity\Type\FuelStation;
use RiverRaid\Data\Entity\Type\RegularHelicopter;
use RiverRaid\Data\Entity\Type\Rock;
use RiverRaid\Data\Entity\Type\Ship;
use RiverRaid\Data\Entity\Type\Tank;
use RiverRaid\Image;

use function sprintf;

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
     * Bit that defines tank location.
     */
    private const BIT_TANK_LOCATION = 5;

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

    private readonly ?Entity $entity;

    public function __construct(
        int $definition,
        private readonly int $position,
    ) {
        $this->entity = $this->newEntity($definition);
    }

    public function isEmpty(): bool
    {
        return $this->entity === null;
    }

    public function toString(): string
    {
        if ($this->entity === null) {
            return 'empty';
        }

        return sprintf('%s at %d', $this->entity->toString(), $this->position);
    }

    public function render(SpriteRepository $sprites, AttributeRepository $attributes, int $y, Image $image): void
    {
        if ($this->entity === null) {
            return;
        }

        $this->entity->render($sprites, $attributes, $image, $this->position, $y);
    }

    private function newEntity(int $definition): ?Entity
    {
        if ($definition === 0) {
            return null;
        }

        if (BinaryUtils::bit($definition, self::BIT_ROCK) === 1) {
            return new Rock($definition & self::BITS_ROCK_TYPE);
        }

        $type = $this->getType($definition);

        return match ($type) {
            Type::HELICOPTER_REGULAR => new RegularHelicopter(
                $type,
                $this->getOrientation($definition),
            ),
            Type::SHIP => new Ship(
                $type,
                $this->getOrientation($definition),
            ),
            Type::HELICOPTER_ADVANCED => new AdvancedHelicopter(
                $type,
                $this->getOrientation($definition),
            ),
            Type::TANK => new Tank(
                $type,
                $this->getOrientation($definition),
                $this->getLocation($definition)
            ),
            Type::FIGHTER => new Fighter(
                $type,
                $this->getOrientation($definition),
            ),
            Type::BALLOON => new Balloon(),
            Type::FUEL_STATION => new FuelStation(),
        };
    }

    private function getType(int $definition): Type
    {
        return Type::from($definition & self::BITS_INTERACTIVE_TYPE);
    }

    private function getOrientation(int $definition): Orientation
    {
        return Orientation::from(BinaryUtils::bit($definition, self::BIT_ORIENTATION));
    }

    private function getLocation(int $definition): Location
    {
        return Location::from(BinaryUtils::bit($definition, self::BIT_TANK_LOCATION));
    }
}
