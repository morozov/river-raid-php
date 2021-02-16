<?php

declare(strict_types=1);

namespace RiverRaid\Data;

/**
 * @psalm-immutable
 */
final class SpriteRepository
{
    /**
     * @param list<list<Sprite>> $threeByOneTileEnemies
     * @param list<Sprite>       $rocks
     */
    public function __construct(
        private array $threeByOneTileEnemies,
        private Sprite $balloon,
        private Sprite $fuelStation,
        private array $rocks,
    ) {
    }

    public function get3By1Enemy(int $orientation, int $type): Sprite
    {
        return $this->threeByOneTileEnemies[1 - $orientation][$type - 1];
    }

    public function getBalloon(): Sprite
    {
        return $this->balloon;
    }

    public function getFuelStation(): Sprite
    {
        return $this->fuelStation;
    }

    public function getRock(int $type): Sprite
    {
        return $this->rocks[$type];
    }
}
