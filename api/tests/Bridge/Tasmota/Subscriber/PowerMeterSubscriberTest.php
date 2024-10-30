<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Tests\Bridge\Tasmota\Subscriber;

use Homebot\Bridge\Tasmota\Subscriber\PowerMeterSubscriber;
use Homebot\Contracts\MqttPayloadInterface;
use Homebot\Entity\Device;
use Homebot\Messenger\Sensor\PowerMeterMessage;
use Homebot\Messenger\StateMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class PowerMeterSubscriberTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testHandler(): void
    {
        $payload = $this->createMock(MqttPayloadInterface::class);
        $meter = $this->createMock(Device::class);
        $bus = $this->createMock(MessageBusInterface::class);
        $json = file_get_contents(__DIR__.'/fixtures/power-meter.json');

        $meter->method('getId')->willReturn($uid = Uuid::v1());
        $meter->driverConfig = ['topic' => 'topic'];

        $payload->expects($this->once())
            ->method('getMessage')
            ->willReturn($json);

        $bus->expects($this->exactly(3))
            ->method('dispatch')
            ->with($this->isInstanceOf(StateMessage::class))
            ->willReturn(new Envelope(new \stdClass()));

        $sut = new PowerMeterSubscriber($bus, $meter);
        $sut->handler($payload);
    }
}
