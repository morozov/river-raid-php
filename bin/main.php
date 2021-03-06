#!/usr/bin/env php
<?php

declare(strict_types=1);

use RiverRaid\Data\Level;
use RiverRaid\Data\Provider\Binary;
use RiverRaid\Image;

require __DIR__ . '/../vendor/autoload.php';

const SIZE_LEVELS = 0x30;

$provider = new Binary(__DIR__ . '/../river-raid.bin', 0x4000);

$terrainFragments = $provider->getTerrainFragments();
$entitySlots      = $provider->getEntitySlots();
$terrainProfiles  = $provider->getTerrainProfiles();
$icelandFragments = $provider->getIslandFragments();
$sprites          = $provider->getSprites();

for ($i = 0; $i < SIZE_LEVELS; $i++) {
    $image = new Image(256, 1024);
    $level = new Level($i);
    $level->render($terrainFragments, $entitySlots, $terrainProfiles, $icelandFragments, $sprites, $image);

    $image->save(__DIR__ . '/../build/level' . sprintf('%02d', $i + 1) . '.png');
}
