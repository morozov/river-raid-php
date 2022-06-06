<?php

declare(strict_types=1);

namespace RiverRaid\Data;

interface Provider
{
    public function getTerrainFragments(
        TerrainProfileRepository $terrainProfileRepository,
        IslandFragmentRepository $islandFragmentRepository,
    ): TerrainFragmentRepository;

    public function getEntitySlots(): EntitySlotRepository;

    public function getTerrainProfiles(): TerrainProfileRepository;

    public function getIslandFragments(TerrainProfileRepository $terrainProfileRepository): IslandFragmentRepository;

    public function getSprites(): SpriteRepository;

    public function getAttributes(): AttributeRepository;
}
