<?php

declare(strict_types=1);

namespace RiverRaid\Data\Provider;

use RiverRaid\Data\IslandFragment;
use RiverRaid\Data\IslandFragmentRegistry;
use RiverRaid\Data\Level;
use RiverRaid\Data\LevelList;
use RiverRaid\Data\Object\Definition;
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
    private const ADDRESS_ISLAND_FRAGMENTS   = 0xC600;
    private const ADDRESS_LEVEL_TERRAIN       = 0x9500;
    private const ADDRESS_TERRAIN_PROFILES    = 0x8063;
    private const ADDRESS_LEVEL_OBJECTS       = 0xC800;
    private const ADDRESS_SPRITE_3BY1_ENEMY   = 0x85B3;
    private const ADDRESS_SPRITE_BALLOON      = 0x8972;
    private const ADDRESS_SPRITE_FUEL_STATION = 0x8A86;
    private const ADDRESS_SPRITE_ROCK         = 0x84A1;

    private const SIZE_ISLAND_FRAGMENT         = 0x03;
    private const SIZE_ISLAND_FRAGMENTS        = 0x23;
    private const SIZE_LEVELS                  = 0x30;
    private const SIZE_LEVEL_OBJECTS           = 0x80;
    private const SIZE_LEVEL_TERRAIN_FRAGMENTS = 0x40;
    private const SIZE_OBJECT                  = 0x02;
    private const SIZE_SPRITE_3BY1_ENEMY       = 0x18;
    private const SIZE_SPRITE_BALLOON          = 0x20;
    private const SIZE_SPRITE_FUEL_STATION     = 0x32;
    private const SIZE_SPRITE_ROCK             = 0x30;
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
        $objects  = $this->readObjectsByLevel();
        $levels   = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = new Level($terrains[$i], $objects[$i]);
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
            $this->readFuelStationBytes(),
            $this->readRockBytes()
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
     * @return list<list<Definition>>
     */
    private function readObjectsByLevel(): array
    {
        $this->seek(self::ADDRESS_LEVEL_OBJECTS);

        $levels = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = $this->readLevelObjects();
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
     * @return list<Definition>
     */
    private function readLevelObjects(): array
    {
        $rows = [];

        for ($i = 0; $i < self::SIZE_LEVEL_OBJECTS; $i++) {
            $rows[] = $this->readObject();
        }

        return $rows;
    }

    private function readTerrainFragment(): TerrainFragment
    {
        return new TerrainFragment(
            ...$this->readBytes(self::SIZE_TERRAIN_FRAGMENT),
        );
    }

    private function readObject(): Definition
    {
        return new Definition(
            ...$this->readBytes(self::SIZE_OBJECT),
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
                            Definition::OBJECT_SHIP => 0x0D,
                            Definition::OBJECT_TANK => 0x20,
                            Definition::OBJECT_FIGHTER => 0x0C,
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
        $this->seek(self::ADDRESS_SPRITE_BALLOON);

        return new Sprite(
            2,
            new Attributes(0x0E),
            $this->readBytes(self::SIZE_SPRITE_BALLOON)
        );
    }

    private function readFuelStationBytes(): Sprite
    {
        $this->seek(self::ADDRESS_SPRITE_FUEL_STATION);

        return new Sprite(
            2,
            new Attributes(0x0B),
            $this->readBytes(self::SIZE_SPRITE_FUEL_STATION)
        );
    }

    /**
     * @return list<Sprite>
     */
    private function readRockBytes(): array
    {
        $this->seek(self::ADDRESS_SPRITE_ROCK);

        $bytes = [];

        for ($i = 0; $i < self::SIZE_SPRITE_ROCKS; $i++) {
            $bytes[] = new Sprite(
                3,
                new Attributes(0x14),
                $this->readBytes(self::SIZE_SPRITE_ROCK)
            );
        }

        return $bytes;
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
}
