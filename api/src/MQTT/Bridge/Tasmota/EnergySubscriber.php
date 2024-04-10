<?php

namespace Homebot\MQTT\Bridge\Tasmota;

use Homebot\Entity\EnergySensor;
use Homebot\MQTT\Payload;
use Homebot\MQTT\AbstractSubscriber;
use Homebot\Sensor\EnergyUpdate;
use Symfony\Component\Messenger\MessageBusInterface;

class EnergySubscriber extends AbstractSubscriber
{
    private string $topic;

    public function __construct(
        private EnergySensor $sensor,
        private MessageBusInterface $bus
    )
    {
        $data = $sensor->driverInfo;
        $this->topic = $data['topic'];
    }

    public function handle(Payload $payload): void
    {
        $bus = $this->bus;
        $sensor = $this->sensor;

        $json = json_decode($payload->message, true);
        $data = $json['ENERGY'];
        $message = new EnergyUpdate(
            $sensor,
            $data['Today'],
            $data['ApparentPower'],
            $data['Voltage'],
            $data['Current']
        );
        $bus->dispatch($message);
    }

    public function getTopic(): string
    {
        return $this->topic;;
    }
}
