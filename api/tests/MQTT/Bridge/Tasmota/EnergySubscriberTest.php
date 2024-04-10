<?php

namespace Homebot\Tests\MQTT\Bridge\Tasmota;

use Doctrine\Persistence\ObjectManager;
use Homebot\Entity\EnergySensor;
use Homebot\MQTT\Bridge\Tasmota\EnergySubscriber;
use Homebot\MQTT\Payload;
use Homebot\Sensor\EnergyUpdate;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class EnergySubscriberTest extends TestCase
{
    public function testHandle()
    {
        $json = file_get_contents(__DIR__.'/fixtures/energy.json');
        $payload = new Payload('topic', $json);
        $energy = new EnergySensor();
        $bus = $this->createMock(MessageBusInterface::class);

        $energy->driverInfo = [
            'topic' => 'tele/em-homelab/SENSOR',
        ];

        $bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(EnergyUpdate::class))
            ->willReturn(new Envelope(new stdClass()));
        ;

        $subscriber = new EnergySubscriber($energy, $bus);
        $subscriber->handle($payload);

        $this->assertEquals('tele/em-homelab/SENSOR', $subscriber->getTopic());
    }
}
