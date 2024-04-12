<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Bridge\Tasmota\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Homebot\Bridge\Tasmota\TasmotaEvents;
use Homebot\Contracts\Repository\StatisticMetaRepositoryInterface;
use Homebot\Entity\Device;
use Homebot\Entity\StatisticMeta;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use function is_null;

#[AsEventListener(event: TasmotaEvents::CONFIGURE_POWER_METER)]
class ConfigurePowerMeterListener
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(Device $meter): void
    {
        $this->ensureMeta($meter, 'today', 'kWh', true, true);
        $this->ensureMeta($meter, 'total', 'kWh', true, true);
        $this->ensureMeta($meter, 'voltage', 'volt', true, false);
        $this->ensureMeta($meter, 'current', 'ampere', true, false);
        $this->ensureMeta($meter, 'power', 'watt', true, false);
    }

    private function ensureMeta(Device $meter, string $idSuffix, string $unit, bool $hasMean, bool $hasSum): void
    {
        /** @var StatisticMetaRepositoryInterface $metaRepo */
        $metaRepo = $this->em->getRepository(StatisticMeta::class);
        $sensorId = $meter->getSensorId().'_'.$idSuffix;

        $metaRepo->ensureMeta($meter, $sensorId, $unit, $hasMean, $hasSum);


    }
}
