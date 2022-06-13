<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Platform\Attributes;

/** @psalm-immutable */
final class AttributeRepository
{
    public function __construct(
        private readonly Attributes $helicopter,
        private readonly Attributes $ship,
        private readonly Attributes $tankOnBank,
        private readonly Attributes $tankOnBridge,
        private readonly Attributes $fighter,
        private readonly Attributes $balloon,
        private readonly Attributes $fuelStation,
        private readonly Attributes $rock,
    ) {
    }

    public function getHelicopterAttributes(): Attributes
    {
        return $this->helicopter;
    }

    public function getShipAttributes(): Attributes
    {
        return $this->ship;
    }

    public function getTankOnBankAttributes(): Attributes
    {
        return $this->tankOnBank;
    }

    public function getTankOnBridgeAttributes(): Attributes
    {
        return $this->tankOnBridge;
    }

    public function getFighterAttributes(): Attributes
    {
        return $this->fighter;
    }

    public function getBalloonAttributes(): Attributes
    {
        return $this->balloon;
    }

    public function getFuelStationAttributes(): Attributes
    {
        return $this->fuelStation;
    }

    public function getRockAttributes(): Attributes
    {
        return $this->rock;
    }
}
