<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Tests\Bridge\Tasmota\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Homebot\Bridge\Tasmota\Listener\ConfigurePowerMeterListener;
use Homebot\Contracts\Repository\StatisticMetaRepositoryInterface;
use Homebot\Entity\Device;
use Homebot\Entity\StatisticMeta;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ConfigurePowerMeterListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $metaRepo = $this->createMock(StatisticMetaRepositoryInterface::class);
        $meter = $this->createMock(Device::class);
        $sut = new ConfigurePowerMeterListener($em);

        $em->method('getRepository')->with(StatisticMeta::class)->willReturn($metaRepo);
        $meter->method('getId')->willReturn($meterId = Uuid::v1());

        $meter->name = 'meter name';
        $meter->deviceId = 'meter_name';
        $meter->method('getSensorId')->willReturn('sensor.meter_name');

        $meta = $this->createMock(StatisticMeta::class);

        $metaRepo->expects($this->exactly(5))
            ->method('ensureMeta')
            ->willReturnMap([
                [$meter, 'sensor.meter_name_today', 'kWh', true, true, $meta],
                [$meter, 'sensor.meter_name_total', 'kWh', true, true, $meta],
                [$meter, 'sensor.meter_name_voltage', 'volt', true, false, $meta],
                [$meter, 'sensor.meter_name_current', 'ampere', true, false, $meta],
                [$meter, 'sensor.meter_name_power', 'watt', true, false, $meta],
            ]);

        $sut($meter);
    }
}
