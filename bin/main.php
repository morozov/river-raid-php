#!/usr/bin/env php
<?php

declare(strict_types=1);

use RiverRaid\Data\Provider\Binary;
use RiverRaid\Image;
use RiverRaid\UnpackScene;

require __DIR__ . '/../vendor/autoload.php';

$provider = new Binary(__DIR__ . '/../river-raid.bin', 0x4000);

$profiles         = $provider->getTerrainProfiles();
$levels           = $provider->getLevels();
$icelandFragments = $provider->getIslandFragments();
$sprites          = $provider->getSprites();

$unpackScene = new UnpackScene($profiles, $icelandFragments);

foreach ($levels->levels as $i => $level) {
    $image = new Image(256, 1024);
    $ink   = $image->allocateColor(0, 197, 0);

    $scene = $unpackScene($level);

    foreach ($scene->terrainLines as $y => $terrainLine) {
        $terrainLine->render($image, convertY($y), $ink);
    }

    foreach ($level->slots as $y => $slot) {
        $slot->render($sprites, $image, convertY($y * 8));
    }

    $image->save(__DIR__ . '/../build/level' . sprintf('%02d', $i + 1) . '.png');
}

function convertY(int $y): int
{
    return 1024 - 1 - ($y - 48 + 1024) % 1024;
}
