<?php

declare(strict_types=1);

namespace RiverRaid\Data;

interface Provider
{
    public function getLevels(): LevelList;

    public function getTerrainProfiles(): TerrainProfileRegistry;

    public function getIslandFragments(): IslandFragmentRegistry;
}
