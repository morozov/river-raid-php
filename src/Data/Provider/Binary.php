<?php

declare(strict_types=1);

namespace RiverRaid\Data\Provider;

use Override;
use RiverRaid\Data\AttributeRepository;
use RiverRaid\Data\EntitySlot;
use RiverRaid\Data\EntitySlotRepository;
use RiverRaid\Data\IslandFragment;
use RiverRaid\Data\IslandFragmentRepository;
use RiverRaid\Data\Provider;
use RiverRaid\Data\Sprite;
use RiverRaid\Data\SpriteRepository;
use RiverRaid\Data\TerrainFragment;
use RiverRaid\Data\TerrainFragmentRepository;
use RiverRaid\Data\TerrainProfile;
use RiverRaid\Data\TerrainProfile\CanalTerrainProfile;
use RiverRaid\Data\TerrainProfile\RegularTerrainProfile;
use RiverRaid\Data\TerrainProfile\RenderingMode;
use RiverRaid\Data\TerrainProfile\RoadAndBridgeTerrainProfile;
use RiverRaid\Data\TerrainProfileRepository;
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

    private const SIZE_LEVEL_TERRAIN_FRAGMENTS = 0x40;
    private const SIZE_LEVEL_ENTITY_SLOTS      = 0x80;
    private const SIZE_LEVELS                  = 0x30;

    private const SIZE_ISLAND_FRAGMENT     = 0x03;
    private const SIZE_ISLAND_FRAGMENTS    = 0x23;
    private const SIZE_ENTITY_SLOT         = 0x02;
    private const SIZE_SPRITE_3BY1_ENEMY   = 0x18;
    private const SIZE_SPRITE_FUEL_STATION = 0x32;
    private const SIZE_SPRITE_ROCKS        = 0x04;
    private const SIZE_SPRITE_FRAMES       = 0x04;
    private const SIZE_TYPE_3BY1_ENEMY     = 0x05;
    private const SIZE_TERRAIN_FRAGMENT    = 0x04;
    private const SIZE_TERRAIN_PROFILE     = 0x10;
    private const SIZE_TERRAIN_PROFILES    = 0x0F;

    /** @var resource */
    private $stream;

    public function __construct(string $path, private readonly int $startAddress)
    {
        $stream = fopen($path, 'rb');

        if ($stream === false) {
            throw new RuntimeException();
        }

        $this->stream = $stream;
    }

    #[Override]
    public function getTerrainFragments(
        TerrainProfileRepository $terrainProfileRepository,
        IslandFragmentRepository $islandFragmentRepository,
    ): TerrainFragmentRepository {
        $this->seek(self::ADDRESS_LEVEL_TERRAIN);

        $fragments = [];

        for ($i = 0; $i < self::SIZE_LEVEL_TERRAIN_FRAGMENTS * self::SIZE_LEVELS; $i++) {
            [$profileNumber, $byte2, $byte3, $byte4] = $this->readBytes(self::SIZE_TERRAIN_FRAGMENT);

            $terrainProfile = $terrainProfileRepository->getProfile($profileNumber);
            $renderingMode  = RenderingMode::from($byte4 & 3);

            $islandFragmentNumber = $byte4 >> 2;

            if ($islandFragmentNumber > 0) {
                $islandFragment = $islandFragmentRepository->getFragment($islandFragmentNumber);
            } else {
                $islandFragment = null;
            }

            $fragments[] = new TerrainFragment($terrainProfile, $byte2, $byte3, $renderingMode, $islandFragment);
        }

        return new TerrainFragmentRepository($fragments);
    }

    #[Override]
    public function getEntitySlots(): EntitySlotRepository
    {
        $this->seek(self::ADDRESS_LEVEL_ENTITY_SLOTS);

        $slots = [];

        for ($i = 0; $i < self::SIZE_LEVEL_ENTITY_SLOTS * self::SIZE_LEVELS; $i++) {
            $slots[] = new EntitySlot(
                ...$this->readBytes(self::SIZE_ENTITY_SLOT),
            );
        }

        return new EntitySlotRepository($slots);
    }

    #[Override]
    public function getTerrainProfiles(): TerrainProfileRepository
    {
        $this->seek(self::ADDRESS_TERRAIN_PROFILES);

        $profiles = [];

        for ($i = 0; $i < self::SIZE_TERRAIN_PROFILES; $i++) {
            $profiles[] = $this->readTerrainProfile();
        }

        return new TerrainProfileRepository($profiles);
    }

    #[Override]
    public function getIslandFragments(TerrainProfileRepository $terrainProfileRepository): IslandFragmentRepository
    {
        $this->seek(self::ADDRESS_ISLAND_FRAGMENTS);

        $fragments = [];

        for ($i = 0; $i < self::SIZE_ISLAND_FRAGMENTS; $i++) {
            $fragments[] = $this->readIslandFragment($terrainProfileRepository);
        }

        return new IslandFragmentRepository($fragments);
    }

    #[Override]
    public function getSprites(): SpriteRepository
    {
        return new SpriteRepository(
            $this->read3By1EnemyBytes(),
            $this->readBalloonBytes(),
            $this->readFuelStationSprite(),
            $this->readRockSprites(),
        );
    }

    #[Override]
    public function getAttributes(): AttributeRepository
    {
        return new AttributeRepository(
            new Attributes(0x0E),
            new Attributes(0x0D),
            new Attributes(0x20),
            new Attributes(0x04),
            new Attributes(0x0C),
            new Attributes(
                $this->seekAndReadByte(self::ADDRESS_SPRITE_BALLOON_ATTRIBUTES),
            ),
            new Attributes(
                $this->seekAndReadByte(self::ADDRESS_SPRITE_FUEL_STATION_ATTRIBUTES),
            ),
            new Attributes(
                $this->seekAndReadByte(self::ADDRESS_SPRITE_ROCK_ATTRIBUTES),
            ),
        );
    }

    private function readTerrainProfile(): TerrainProfile
    {
        $bytes = $this->readBytes(self::SIZE_TERRAIN_PROFILE);

        return match ($bytes[0]) {
            0x80,
            0xE0 => new CanalTerrainProfile(),
            0xC0 => new RoadAndBridgeTerrainProfile(),
            default => new RegularTerrainProfile($bytes),
        };
    }

    private function readIslandFragment(TerrainProfileRepository $terrainProfileRepository): IslandFragment
    {
        [$byte1, $byte2, $byte3] = $this->readBytes(self::SIZE_ISLAND_FRAGMENT);

        return new IslandFragment(
            $terrainProfileRepository->getProfile($byte1),
            $byte2,
            RenderingMode::from($byte3),
        );
    }

    /** @return list<list<Sprite>> */
    private function read3By1EnemyBytes(): array
    {
        $this->seek(self::ADDRESS_SPRITE_3BY1_ENEMY);

        $bytes = [];

        for ($orientation = 0; $orientation < 2; $orientation++) {
            $orientationBytes = [];

            for ($type = 1; $type <= self::SIZE_TYPE_3BY1_ENEMY; $type++) {
                $orientationBytes[] = new Sprite(
                    3,
                    $this->readBytes(self::SIZE_SPRITE_3BY1_ENEMY),
                );
                $this->advance(self::SIZE_SPRITE_3BY1_ENEMY * (self::SIZE_SPRITE_FRAMES - 1));
            }

            $bytes[] = $orientationBytes;
        }

        return $bytes;
    }

    private function readBalloonBytes(): Sprite
    {
        $size  = $this->seekAndReadByte(self::ADDRESS_SPRITE_BALLOON_SIZE);
        $width = $this->seekAndReadByte(self::ADDRESS_SPRITE_BALLOON_WIDTH);

        $this->seek(self::ADDRESS_SPRITE_BALLOON_PIXELS);

        return new Sprite(
            $width,
            $this->readBytes($size),
        );
    }

    private function readFuelStationSprite(): Sprite
    {
        $width = $this->seekAndReadByte(self::ADDRESS_SPRITE_FUEL_STATION_WIDTH);

        $this->seek(self::ADDRESS_SPRITE_FUEL_STATION_PIXELS);

        return new Sprite(
            $width,
            $this->readBytes(self::SIZE_SPRITE_FUEL_STATION),
        );
    }

    /** @return list<Sprite> */
    private function readRockSprites(): array
    {
        $size  = $this->seekAndReadByte(self::ADDRESS_SPRITE_ROCK_SIZE);
        $width = $this->seekAndReadByte(self::ADDRESS_SPRITE_ROCK_WIDTH);

        $this->seek(self::ADDRESS_SPRITE_ROCK_PIXELS);

        $sprites = [];

        for ($i = 0; $i < self::SIZE_SPRITE_ROCKS; $i++) {
            $sprites[] = new Sprite(
                $width,
                $this->readBytes($size),
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

    /** @return positive-int */
    private function readByte(): int
    {
        return $this->readBytes(1)[0];
    }

    /** @return positive-int */
    private function seekAndReadByte(int $address): int
    {
        $this->seek($address);

        return $this->readByte();
    }
}
