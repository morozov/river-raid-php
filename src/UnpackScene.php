<?php

declare(strict_types=1);

namespace RiverRaid;

final class UnpackScene
{
    public function __construct(
        private TerrainProfiles $profiles,
    ) {
    }

    public function __invoke(TerrainLevel $level): Scene
    {
        $values = [];

        foreach ($level->rows as $i => $row) {
            $profile = $this->profiles->profiles[$row->byte1 - 1];

            foreach ($profile->values as $value) {
                $values[] = $row->byte3 + $value - 6;
            }
        }

        return new Scene($values);
    }
}
