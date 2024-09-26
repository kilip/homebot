<?php

namespace Homebot\Contracts\Repository;

use Homebot\Entity\Statistic;
use Symfony\Component\Uid\Uuid;

interface StatisticRepositoryInterface
{
    public function findLast(Uuid $deviceId, Uuid $metaId): Statistic;
}
