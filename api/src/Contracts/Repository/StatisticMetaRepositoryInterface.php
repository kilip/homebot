<?php

namespace Homebot\Contracts\Repository;

use Homebot\Entity\Device;
use Homebot\Entity\StatisticMeta;
use Symfony\Component\Uid\Uuid;

interface StatisticMetaRepositoryInterface
{
    public function findBySensor(string $sensorId): ?StatisticMeta;

    public function store(StatisticMeta $meta): StatisticMeta;

    public function ensureMeta(Device $meter, string $idSuffix, string $unit, bool $hasMean, bool $hasSum): StatisticMeta;
}
