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
    $scene = $unpackScene($level);

    foreach (array_reverse($scene->terrainLeft) as $width) {
        echo str_repeat('░', $width), PHP_EOL;
    }

    break;
}
