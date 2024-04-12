<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Tests\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Homebot\Configuration;
use Homebot\Contracts\Repository\PowerMeterRepositoryInterface;
use Homebot\Entity\Device;
use Homebot\Events;
use Homebot\Listener\LoadSensorListener;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LoadSensorListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $meter = $this->createMock(EntityManagerInterface::class);
        $meterRepo = $this->createMock(PowerMeterRepositoryInterface::class);
        $configuration = new Configuration(__DIR__.'/fixtures');
        $sut = new LoadSensorListener($dispatcher, $meter);
        $powerMeter = new Device();


        $meter->expects($this->once())
            ->method('getRepository')
            ->with(Device::class)
            ->willReturn($meterRepo);

        $meterRepo->expects($this->once())
            ->method('findByName')
            ->with('meter name')
            ->willReturn($powerMeter);

        $meter->expects($this->once())
            ->method('persist')
            ->with($powerMeter);
        $meter->expects($this->once())
            ->method('flush')
            ->with();

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($powerMeter, Events::CONFIGURE_POWER_METER.'.some_driver');

        $sut($configuration);
    }
}
