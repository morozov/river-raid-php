<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Data\Entity\Property\Orientation;
use RiverRaid\Data\Entity\Type;

/** @psalm-immutable */
final class SpriteRepository
{
    /**
     * @param list<list<Sprite>> $threeByOneTileEnemies
     * @param list<Sprite>       $rocks
     */
    public function __construct(
        private readonly array $threeByOneTileEnemies,
        private readonly Sprite $balloon,
        private readonly Sprite $fuelStation,
        private readonly array $rocks,
    ) {
    }

    public function getThreeByOneTileEnemySprite(Orientation $orientation, Type $type): Sprite
    {
        return $this->threeByOneTileEnemies[1 - $orientation->value][$type->value - 1];
    }

    public function getBalloonSprite(): Sprite
    {
        return $this->balloon;
    }

    public function getFuelStationSprite(): Sprite
    {
        return $this->fuelStation;
    }

    public function getRockSprite(int $type): Sprite
    {
        return $this->rocks[$type];
    }
}
