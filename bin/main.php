#!/usr/bin/env php
<?php

declare(strict_types=1);

use RiverRaid\Data\Provider\Binary;
use RiverRaid\UnpackScene;

require __DIR__ . '/../vendor/autoload.php';

$provider = new Binary(__DIR__ . '/../river-raid.bin', 0x4000);

$profiles         = $provider->getTerrainProfiles();
$levels           = $provider->getTerrainLevels();
$icelandFragments = $provider->getIslandFragments();

$unpackScene = new UnpackScene($profiles, $icelandFragments);

foreach ($levels->levels as $i => $level) {
    $image = imagecreate(256, 1024);
    $paper = imagecolorallocate($image, 0, 0, 197);
    $ink   = imagecolorallocate($image, 0, 197, 0);
    $scene = $unpackScene($level);

    foreach ($scene->terrainLines as $y => $terrainLine) {
        $riverBankLines = $terrainLine->riverBankLines;
        $islandLine     = $terrainLine->islandLine;

        terrainLine($image, 0, $riverBankLines->left, $y, $ink);
        terrainLine($image, $riverBankLines->right, 255, $y, $ink);

        if ($islandLine === null) {
            continue;
        }

        terrainLine($image, $islandLine->left, $islandLine->right, $y, $ink);
    }

    imagepng($image, __DIR__ . '/../build/level' . sprintf('%02d', $i + 1) . '.png');
    imagedestroy($image);
}

function terrainLine(GdImage $image, int $x1, int $x2, int $y, int $ink): void
{
    $y = 1024 - 1 - ($y - 48 + 1024) % 1024;
    imageline($image, $x1, $y, $x2, $y, $ink);
}
