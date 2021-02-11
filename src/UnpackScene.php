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

                $terrainLeft[]  = $coordinateLeft - 6;
                $terrainRight[] = $this->calcOtherSide($terrainRow->byte2, $coordinateLeft, $mode);

                if ($islandProfile !== null && $islandRow !== null) {
                    $side1 = 0x80 + $islandRow->byte2 + $islandProfile->values[$rowLine];
                    $side2 = $this->calcOtherSide(
                        0x3C,
                        $islandRow->byte2 + $islandProfile->values[$rowLine],
                        $islandRow->byte3
                    );

                    $islands[$levelLine] = [$side2, $side1 + 10];
                }

                $levelLine++;
            }
        }

        return new Scene($terrainLeft, $terrainRight, $islands);
    }

    private function calcOtherSide(int $c, int $d, int $mode): int
    {
        if ($mode === 1) {
            return 2 * $c - $d;
        }

        if ($mode === 2) {
            return $c + $d;
        }

        throw new LogicException();
    }
}
