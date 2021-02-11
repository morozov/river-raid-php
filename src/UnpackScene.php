<?php

declare(strict_types=1);

namespace RiverRaid;

use LogicException;

final class UnpackScene
{
    public function __construct(
        private TerrainProfiles $profiles,
        private IslandRows $islandRows,
    ) {
    }

    public function __invoke(TerrainLevel $level): Scene
    {
        $terrainLeft  = [];
        $terrainRight = [];
        $islands      = [];
        $levelLine    = 0;

        foreach ($level->rows as $i => $terrainRow) {
            $terrainProfile = $this->profiles->profiles[$terrainRow->byte1 - 1];

            $mode        = $terrainRow->byte4 & 3;
            $islandIndex = $terrainRow->byte4 >> 2;

            $islandProfile = null;
            $islandRow     = null;

            if ($islandIndex > 0) {
                $islandRow     = $this->islandRows->rows[$islandIndex - 1];
                $islandProfile = $this->profiles->profiles[$islandRow->byte1 - 1];
            }

            foreach ($terrainProfile->values as $rowLine => $value) {
                $coordinateLeft = $terrainRow->byte3 + $value;

                if ($mode === 1) {
                    $coordinateRight = 2 * $terrainRow->byte2 - $coordinateLeft;
                } elseif ($mode === 2) {
                    $coordinateRight = $terrainRow->byte2 + $coordinateLeft;
                } else {
                    throw new LogicException();
                }

                $terrainLeft[]  = $coordinateLeft - 6;
                $terrainRight[] = $coordinateRight;

                if ($islandProfile !== null && $islandRow !== null) {
                    $islands[$levelLine] = [0x80, 0x80 + $islandRow->byte2 + $islandProfile->values[$rowLine] + 10];
                }

                $levelLine++;
            }
        }

        return new Scene($terrainLeft, $terrainRight, $islands);
    }
}
