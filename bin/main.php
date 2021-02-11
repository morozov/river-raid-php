#!/usr/bin/env php
<?php

declare(strict_types=1);

use RiverRaid\Provider\Binary;
use RiverRaid\UnpackScene;

require __DIR__ . '/../vendor/autoload.php';

$provider = new Binary(__DIR__ . '/../river-raid.bin', 0x4000);

$profiles    = $provider->getTerrainProfiles();
$levels      = $provider->getTerrainLevels();
$icelandRows = $provider->getIslandRows();

$unpackScene = new UnpackScene($profiles, $icelandRows);

foreach ($levels->levels as $i => $level) {
    $image = imagecreate(256, 1024);
    $paper = imagecolorallocate($image, 0, 0, 197);
    $ink   = imagecolorallocate($image, 0, 197, 0);
    $scene = $unpackScene($level);

    foreach ($scene->terrainLeft as $y => $x) {
        terrainLine($image, 0, $x, $y, $ink);
    }

    foreach ($scene->terrainRight as $y => $x) {
        terrainLine($image, 256, $x, $y, $ink);
    }

    foreach ($scene->islands as $y => [$x1, $x2]) {
        terrainLine($image, $x1, $x2, $y, $ink);
    }

    imagepng($image, __DIR__ . '/../build/level' . sprintf('%02d', $i + 1) . '.png');
    imagedestroy($image);
}

function terrainLine($image, $x1, $x2, $y, $ink): void
{
    imageline($image, $x1, 1023 - $y, $x2, 1023 - $y, $ink);
}
