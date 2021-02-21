<?php

declare(strict_types=1);

namespace RiverRaid\Data\Provider;

use RiverRaid\Data\Entity;
use RiverRaid\Data\EntitySlot;
use RiverRaid\Data\IslandFragment;
use RiverRaid\Data\IslandFragmentRegistry;
use RiverRaid\Data\Level;
use RiverRaid\Data\LevelList;
use RiverRaid\Data\Provider;
use RiverRaid\Data\Sprite;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Data\TerrainFragment;
use RiverRaid\Data\TerrainProfile;
use RiverRaid\Data\TerrainProfileRegistry;
use RiverRaid\Platform\Attributes;
use RuntimeException;

use function array_values;
use function fopen;
use function fread;
use function fseek;
use function strlen;
use function unpack;

use const SEEK_CUR;

final class Binary implements Provider
{
    private const ADDRESS_ISLAND_FRAGMENTS  = 0xC600;
    private const ADDRESS_LEVEL_TERRAIN      = 0x9500;
    private const ADDRESS_TERRAIN_PROFILES   = 0x8063;
    private const ADDRESS_LEVEL_ENTITY_SLOTS = 0xC800;
    private const ADDRESS_SPRITE_3BY1_ENEMY  = 0x85B3;

    private const ADDRESS_SPRITE_BALLOON_SIZE       = 0x7083;
    private const ADDRESS_SPRITE_BALLOON_WIDTH      = 0x7086;
    private const ADDRESS_SPRITE_BALLOON_ATTRIBUTES = 0x7088;
    private const ADDRESS_SPRITE_BALLOON_PIXELS     = 0x8972;

    private const ADDRESS_SPRITE_FUEL_STATION_WIDTH      = 0x7064;
    private const ADDRESS_SPRITE_FUEL_STATION_ATTRIBUTES = 0x7066;
    private const ADDRESS_SPRITE_FUEL_STATION_PIXELS     = 0x8A86;

    private const ADDRESS_SPRITE_ROCK_SIZE       = 0x6FC3;
    private const ADDRESS_SPRITE_ROCK_WIDTH      = 0x6FDE;
    private const ADDRESS_SPRITE_ROCK_ATTRIBUTES = 0x6FE0;
    private const ADDRESS_SPRITE_ROCK_PIXELS     = 0x84A1;

    private const SIZE_ISLAND_FRAGMENT         = 0x03;
    private const SIZE_ISLAND_FRAGMENTS        = 0x23;
    private const SIZE_LEVELS                  = 0x30;
    private const SIZE_LEVEL_ENTITY_SLOTS      = 0x80;
    private const SIZE_LEVEL_TERRAIN_FRAGMENTS = 0x40;
    private const SIZE_ENTITY_SLOT             = 0x02;
    private const SIZE_SPRITE_3BY1_ENEMY       = 0x18;
    private const SIZE_SPRITE_FUEL_STATION     = 0x32;
    private const SIZE_SPRITE_ROCKS            = 0x04;
    private const SIZE_SPRITE_FRAMES           = 0x04;
    private const SIZE_TYPE_3BY1_ENEMY         = 0x05;
    private const SIZE_TERRAIN_FRAGMENT        = 0x04;
    private const SIZE_TERRAIN_PROFILE         = 0x10;
    private const SIZE_TERRAIN_PROFILES        = 0x0F;

    /** @var resource */
    private $stream;

    public function __construct(string $path, private int $startAddress)
    {
        $stream = fopen($path, 'rb');

        if ($stream === false) {
            throw new RuntimeException();
        }

        $this->stream = $stream;
    }

    public function getLevels(): LevelList
    {
        $terrains = $this->readTerrainsByLevel();
        $entities = $this->readEntitySlotsByLevel();
        $levels   = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = new Level($terrains[$i], $entities[$i]);
        }

        return new LevelList($levels);
    }

    public function getTerrainProfiles(): TerrainProfileRegistry
    {
        $this->seek(self::ADDRESS_TERRAIN_PROFILES);

        $profiles = [];

        for ($i = 0; $i < self::SIZE_TERRAIN_PROFILES; $i++) {
            $profiles[] = $this->readTerrainProfile();
        }

        return new TerrainProfileRegistry($profiles);
    }

    public function getIslandFragments(): IslandFragmentRegistry
    {
        $this->seek(self::ADDRESS_ISLAND_FRAGMENTS);

        $fragments = [];

        for ($i = 0; $i < self::SIZE_ISLAND_FRAGMENTS; $i++) {
            $fragments[] = $this->readIslandFragment();
        }

        return new IslandFragmentRegistry($fragments);
    }

    public function getSprites(): SpriteRepository
    {
        return new SpriteRepository(
            $this->read3By1EnemyBytes(),
            $this->readBalloonBytes(),
            $this->readFuelStationSprite(),
            $this->readRockSprites()
        );
    }

    /**
     * @return list<list<TerrainFragment>>
     */
    private function readTerrainsByLevel(): array
    {
        $this->seek(self::ADDRESS_LEVEL_TERRAIN);

        $levels = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = $this->readLevelTerrain();
        }

        return $levels;
    }

    /**
     * @return list<list<EntitySlot>>
     */
    private function readEntitySlotsByLevel(): array
    {
        $this->seek(self::ADDRESS_LEVEL_ENTITY_SLOTS);

        $levels = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = $this->readLevelEntitySlots();
        }

        return $levels;
    }

    /**
     * @return list<TerrainFragment>
     */
    private function readLevelTerrain(): array
    {
        $fragments = [];

        for ($i = 0; $i < self::SIZE_LEVEL_TERRAIN_FRAGMENTS; $i++) {
            $fragments[] = $this->readTerrainFragment();
        }

        return $fragments;
    }

    /**
     * @return list<EntitySlot>
     */
    private function readLevelEntitySlots(): array
    {
        $slots = [];

        for ($i = 0; $i < self::SIZE_LEVEL_ENTITY_SLOTS; $i++) {
            $slots[] = $this->readEntitySlot();
        }

        return $slots;
    }

    private function readTerrainFragment(): TerrainFragment
    {
        return new TerrainFragment(
            ...$this->readBytes(self::SIZE_TERRAIN_FRAGMENT),
        );
    }

    private function readEntitySlot(): EntitySlot
    {
        return new EntitySlot(
            ...$this->readBytes(self::SIZE_ENTITY_SLOT),
        );
    }

    private function readTerrainProfile(): TerrainProfile
    {
        return new TerrainProfile(
            $this->readBytes(self::SIZE_TERRAIN_PROFILE),
        );
    }

    private function readIslandFragment(): IslandFragment
    {
        return new IslandFragment(
            ...$this->readBytes(self::SIZE_ISLAND_FRAGMENT),
        );
    }

    /**
     * @return list<list<Sprite>>
     */
    private function read3By1EnemyBytes(): array
    {
        $this->seek(self::ADDRESS_SPRITE_3BY1_ENEMY);

        $bytes = [];

        foreach ([0, 1] as $orientation) {
            $orientationBytes = [];

            for ($type = 1; $type <= self::SIZE_TYPE_3BY1_ENEMY; $type++) {
                $orientationBytes[] = new Sprite(
                    3,
                    new Attributes(
                        match ($type) {
                            Entity::TYPE_SHIP => 0x0D,
                            Entity::TYPE_TANK => 0x20,
                            Entity::TYPE_FIGHTER => 0x0C,
                            default => 0x0E,
                        }
                    ),
                    $this->readBytes(self::SIZE_SPRITE_3BY1_ENEMY)
                );
                $this->advance(self::SIZE_SPRITE_3BY1_ENEMY * (self::SIZE_SPRITE_FRAMES - 1));
            }

            $bytes[] = $orientationBytes;
        }

        return $bytes;
    }

    private function readBalloonBytes(): Sprite
    {
        $this->seek(self::ADDRESS_SPRITE_BALLOON_SIZE);
        $size = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_BALLOON_WIDTH);
        $width = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_BALLOON_ATTRIBUTES);
        $attributes = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_BALLOON_PIXELS);

        return new Sprite(
            $width,
            new Attributes($attributes),
            $this->readBytes($size)
        );
    }

    private function readFuelStationSprite(): Sprite
    {
        $this->seek(self::ADDRESS_SPRITE_FUEL_STATION_WIDTH);
        $width = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_FUEL_STATION_ATTRIBUTES);
        $attributes = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_FUEL_STATION_PIXELS);

        return new Sprite(
            $width,
            new Attributes($attributes),
            $this->readBytes(self::SIZE_SPRITE_FUEL_STATION)
        );
    }

    /**
     * @return list<Sprite>
     */
    private function readRockSprites(): array
    {
        $this->seek(self::ADDRESS_SPRITE_ROCK_SIZE);
        $size = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_ROCK_WIDTH);
        $width = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_ROCK_ATTRIBUTES);
        $attributes = $this->readByte();

        $this->seek(self::ADDRESS_SPRITE_ROCK_PIXELS);

        $sprites = [];

        for ($i = 0; $i < self::SIZE_SPRITE_ROCKS; $i++) {
            $sprites[] = new Sprite(
                $width,
                new Attributes($attributes),
                $this->readBytes($size)
            );
        }

        return $sprites;
    }

    private function seek(int $address): void
    {
        fseek($this->stream, $address - $this->startAddress);
    }

    private function advance(int $offset): void
    {
        fseek($this->stream, $offset, SEEK_CUR);
    }

    /**
     * @param positive-int $size
     *
     * @return list<positive-int>
     */
    private function readBytes(int $size): array
    {
        $chars = fread($this->stream, $size);

        if ($chars === false) {
            throw new RuntimeException();
        }

        if (strlen($chars) !== $size) {
            throw new RuntimeException();
        }

        /** @psalm-var array<positive-int> $bytes */
        $bytes = unpack('C*', $chars);

        return array_values($bytes);
    }

    /**
     * @return positive-int
     */
    private function readByte(): int
    {
        return $this->readBytes(1)[0];
    }
}
