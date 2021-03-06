<?php

declare(strict_types=1);

namespace RiverRaid\Data;

interface Provider
{
    public function getTerrainFragments(): TerrainFragmentRepository;

    public function getEntitySlots(): EntitySlotRepository;

    public function getTerrainProfiles(): TerrainProfileRepository;

    public function getIslandFragments(): IslandFragmentRepository;

    public function getSprites(): SpriteRepository;
}
