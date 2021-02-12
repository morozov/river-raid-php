<?php

declare(strict_types=1);

namespace RiverRaid;

use LogicException;
use RiverRaid\Data\Fragment;
use RiverRaid\Data\IslandFragmentRegistry;
use RiverRaid\Data\TerrainLevel;
use RiverRaid\Data\TerrainProfile;
use RiverRaid\Data\TerrainProfileRegistry;
use RiverRaid\Scene\IslandLine;
use RiverRaid\Scene\RiverBankLines;
use RiverRaid\Scene\TerrainLine;

/**
 * @psalm-immutable
 */
final class UnpackScene
{
    private const RENDERING_MODE_SYMMETRICAL = 1;
    private const RENDERING_MODE_PARALLEL    = 2;

    public function __construct(
        public TerrainProfileRegistry $terrainProfiles,
        public IslandFragmentRegistry $islandFragments,
    ) {
    }

    public function __invoke(TerrainLevel $level): Scene
    {
        $terrainLines = [];

        foreach ($level->fragments as $terrainFragment) {
            $terrainProfile = $this->getFragmentTerrainProfile($terrainFragment);

            $islandFragmentNumber = $terrainFragment->getIslandFragmentNumber();

            $islandProfile  = null;
            $islandFragment = null;

            if ($islandFragmentNumber > 0) {
                $islandFragment = $this->islandFragments->getFragment($islandFragmentNumber);
                $islandProfile  = $this->getFragmentTerrainProfile($islandFragment);
            }

            foreach ($terrainProfile->values as $line => $value) {
                $coordinateLeft = $terrainFragment->byte3 + $value;

                $riverBankLine = new RiverBankLines(
                    $coordinateLeft - 6,
                    $this->calcOtherSide(
                        $terrainFragment->byte2,
                        $coordinateLeft,
                        $terrainFragment->getRenderingMode()
                    ),
                );

                $islandLine = null;

                if ($islandProfile !== null && $islandFragment !== null) {
                    $side1 = 0x80 + $islandFragment->byte2 + $islandProfile->values[$line];
                    $side2 = $this->calcOtherSide(
                        0x3C,
                        $islandFragment->byte2 + $islandProfile->values[$line],
                        $islandFragment->getRenderingMode()
                    );

                    $islandLine = new IslandLine($side2, $side1 + 10);
                }

                $terrainLines[] = new TerrainLine($riverBankLine, $islandLine);
            }
        }

        return new Scene($terrainLines);
    }

    private function getFragmentTerrainProfile(Fragment $fragment): TerrainProfile
    {
        return $this->terrainProfiles->getProfile(
            $fragment->getProfileNumber(),
        );
    }

    private function calcOtherSide(int $c, int $d, int $mode): int
    {
        if ($mode === self::RENDERING_MODE_SYMMETRICAL) {
            return 2 * $c - $d;
        }

        if ($mode === self::RENDERING_MODE_PARALLEL) {
            return $c + $d;
        }

        throw new LogicException();
    }
}
