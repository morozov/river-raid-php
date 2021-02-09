<?php

declare(strict_types=1);

namespace RiverRaid;

use LogicException;

final class UnpackScene
{
    public function __construct(
        private TerrainProfiles $profiles,
    ) {
    }

    public function __invoke(TerrainLevel $level): Scene
    {
        $terrainLeft = [];

        foreach ($level->rows as $i => $row) {
            $profile = $this->profiles->profiles[$row->byte1 - 1];

            foreach ($profile->values as $value) {
                $mode = $row->byte4 & 3;

                $coordinateLeft = $row->byte3 + $value;

                if ($mode === 1) {
                    $coordinateRight = 2 * $row->byte2 - $coordinateLeft;
                } elseif ($mode === 2) {
                    $coordinateRight = $row->byte2 + $coordinateLeft;
                } else {
                    throw new LogicException();
                }

                $terrainLeft[]  = $coordinateLeft - 6;
                $terrainRight[] = $coordinateRight;
            }
        }

        return new Scene($terrainLeft, $terrainRight);
    }
}
