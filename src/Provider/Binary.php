<?php

declare(strict_types=1);

namespace RiverRaid\Provider;

use RiverRaid\Provider;
use RiverRaid\TerrainLevel;
use RiverRaid\TerrainLevels;
use RiverRaid\TerrainProfile;
use RiverRaid\TerrainProfiles;
use RiverRaid\TerrainRow;
use RuntimeException;

use function array_values;
use function fopen;
use function fread;
use function fseek;
use function ord;
use function strlen;
use function unpack;

class Binary implements Provider
{
    private const ADDRESS_LEVEL_TERRAIN    = 0x9500;
    private const ADDRESS_TERRAIN_PROFILES = 0x8063;

    private const SIZE_LEVELS     = 0x30;
    private const SIZE_LEVEL_ROWS = 0x40;
    private const SIZE_PROFILE    = 0x10;
    private const SIZE_PROFILES   = 0x0F;

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

    public function getTerrainLevels(): TerrainLevels
    {
        $this->seek(self::ADDRESS_LEVEL_TERRAIN);

        $levels = [];

        for ($i = 0; $i < self::SIZE_LEVELS; $i++) {
            $levels[] = $this->readTerrainLevel();
        }

        return new TerrainLevels($levels);
    }

    public function getTerrainProfiles(): TerrainProfiles
    {
        $this->seek(self::ADDRESS_TERRAIN_PROFILES);

        $profiles = [];

        for ($i = 0; $i < self::SIZE_PROFILES; $i++) {
            $profiles[] = $this->readTerrainProfile();
        }

        return new TerrainProfiles($profiles);
    }

    private function readTerrainLevel(): TerrainLevel
    {
        $rows = [];

        for ($i = 0; $i < self::SIZE_LEVEL_ROWS; $i++) {
            $rows[] = $this->readTerrainRow();
        }

        return new TerrainLevel($rows);
    }

    private function readTerrainRow(): TerrainRow
    {
        return new TerrainRow(
            $this->readByte(),
            $this->readByte(),
            $this->readByte(),
            $this->readByte(),
        );
    }

    private function readTerrainProfile(): TerrainProfile
    {
        return new TerrainProfile(
            $this->readBytes(self::SIZE_PROFILE),
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
