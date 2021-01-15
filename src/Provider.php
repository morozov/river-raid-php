<?php

declare(strict_types=1);

namespace RiverRaid;

interface Provider
{
    public function getTerrainLevels(): TerrainLevels;

    public function getTerrainProfiles(): TerrainProfiles;
}
