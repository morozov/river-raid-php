#!/usr/bin/env php
<?php

declare(strict_types=1);

use RiverRaid\Provider\Binary;
use RiverRaid\UnpackScene;

require __DIR__ . '/../vendor/autoload.php';

$provider = new Binary(__DIR__ . '/../river-raid.bin', 0x4000);

$profiles = $provider->getTerrainProfiles();
$levels   = $provider->getTerrainLevels();

$unpackScene = new UnpackScene($profiles);

foreach ($levels->levels as $i => $level) {
    $image = imagecreate(256, 1024);
    $paper = imagecolorallocate($image, 0, 0, 197);
    $ink   = imagecolorallocate($image, 0, 197, 0);
    $scene = $unpackScene($level);

    foreach ($scene->terrainLeft as $y => $x) {
        imageline($image, 0, 1023 - $y, $x, 1023 - $y, $ink);
    }

    foreach ($scene->terrainRight as $y => $x) {
        imageline($image, 256, 1023 - $y, $x, 1023 - $y, $ink);
    }

    imagepng($image, __DIR__ . '/../build/level' . sprintf('%02d', $i + 1) . '.png');
    imagedestroy($image);
}
