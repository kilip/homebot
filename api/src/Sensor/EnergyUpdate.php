<?php

namespace Homebot\Sensor;

use Homebot\Entity\EnergySensor;
use Symfony\Component\Uid\Uuid;

/**
 * Provide update to energy sensor
 */
class EnergyUpdate
{
    public function __construct(
        public EnergySensor $sensor,
        public float $today,
        public float $power,
        public float $voltage,
        public float $current
    )
    {
    }
}
