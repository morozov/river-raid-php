<?php

declare(strict_types=1);

namespace RiverRaid\Data;

use RiverRaid\Platform\Attributes;

/**
 * @psalm-immutable
 */
final class AttributeRepository
{
    public function __construct(
        private Attributes $helicopter,
        private Attributes $ship,
        private Attributes $tankOnBank,
        private Attributes $tankOnBridge,
        private Attributes $fighter,
        private Attributes $balloon,
        private Attributes $fuelStation,
        private Attributes $rock,
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
