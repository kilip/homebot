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

use Homebot\Bridge\Tasmota\Listener\RegisterMqttSubscriberListener;
use Homebot\Bridge\Tasmota\Subscriber\PowerMeterSubscriber;
use Homebot\Contracts\MqttClientInterface;
use Homebot\Contracts\Repository\PowerMeterRepositoryInterface;
use Homebot\Entity\Device;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterSubscriberListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $repository = $this->createMock(PowerMeterRepositoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $bus = $this->createMock(MessageBusInterface::class);
        $client = $this->createMock(MqttClientInterface::class);
        $meter = $this->createMock(Device::class);
        $sut = new RegisterMqttSubscriberListener($bus, $repository, $logger);

        $repository->expects($this->once())
            ->method('listByDriver')
            ->with('tasmota')
            ->willReturn([$meter])
        ;

        $meter->name = 'meter-name';
        $meter->driverConfig = [
            'topic' => 'topic',
            'qos' => 0,
        ];

        $client->expects($this->once())
            ->method('addSubscriber')
            ->with($this->isInstanceOf(PowerMeterSubscriber::class));

        $logger->expects($this->once())
            ->method('notice')
            ->with('added subscriber for power meter {0}', ['meter-name']);

        $sut($client);
    }
}
