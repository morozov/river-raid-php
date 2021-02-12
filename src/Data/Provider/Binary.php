<?php

declare(strict_types=1);

namespace RiverRaid\Data\Provider;

use RiverRaid\Data\IslandFragment;
use RiverRaid\Data\IslandFragmentRegistry;
use RiverRaid\Data\Provider;
use RiverRaid\Data\TerrainFragment;
use RiverRaid\Data\TerrainLevel;
use RiverRaid\Data\TerrainLevelList;
use RiverRaid\Data\TerrainProfile;
use RiverRaid\Data\TerrainProfileRegistry;
use RuntimeException;

use function array_values;
use function fopen;
use function fread;
use function fseek;
use function ord;
use function strlen;
use function unpack;

final class Binary implements Provider
{
    private const ADDRESS_ISLAND_FRAGMENTS = 0xC600;
    private const ADDRESS_LEVEL_TERRAIN    = 0x9500;
    private const ADDRESS_TERRAIN_PROFILES = 0x8063;

    private const SIZE_ISLAND_FRAGMENT         = 0x03;
    private const SIZE_ISLAND_FRAGMENTS        = 0x23;
    private const SIZE_LEVELS                  = 0x30;
    private const SIZE_LEVEL_TERRAIN_FRAGMENTS = 0x40;
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

    public function getTerrainLevels(): TerrainLevelList
    {
        $this->seek(self::ADDRESS_LEVEL_TERRAIN);

        $levels = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = $this->readTerrainLevel();
        }

        return new TerrainLevelList($levels);
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

    private function readTerrainLevel(): TerrainLevel
    {
        $fragments = [];

        for ($i = 0; $i < self::SIZE_LEVEL_TERRAIN_FRAGMENTS; $i++) {
            $fragments[] = $this->readTerrainFragment();
        }

        return new TerrainLevel($fragments);
    }

    private function readTerrainFragment(): TerrainFragment
    {
        return new TerrainFragment(
            $this->readByte(),
            $this->readByte(),
            $this->readByte(),
            $this->readByte(),
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

    private function seek(int $address): void
    {
        fseek($this->stream, $address - $this->startAddress);
    }

    /**
     * @return positive-int
     */
    private function readByte(): int
    {
        $char = fread($this->stream, 1);

        if ($char === false) {
            throw new RuntimeException();
        }

        return ord($char);
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
