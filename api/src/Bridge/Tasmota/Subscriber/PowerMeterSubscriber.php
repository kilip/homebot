<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Bridge\Tasmota\Subscriber;

use Homebot\Bridge\Mqtt\AbstractSubscriber;
use Homebot\Bridge\Tasmota\Config;
use Homebot\Contracts\MqttPayloadInterface;
use Homebot\Entity\Device;
use Homebot\Messenger\StateMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class PowerMeterSubscriber extends AbstractSubscriber
{
    public function __construct(
        private MessageBusInterface $bus,
        private Device              $meter,
    ) {
        $config = new Config($meter->driverConfig);

        parent::__construct($config->getTopic(), $config->getQos());
    }

    /**
     * @throws \Exception
     */
    public function handler(MqttPayloadInterface $payload): void
    {
        $meter = $this->meter;
        $message = $payload->getMessage();
        $data = \json_decode($message, true);
        $time = $data['Time'];
        $values = $data['ENERGY'];

        $deviceId = $meter->getId();
        $ts = new \DateTimeImmutable($time);

        $this->bus->dispatch(new StateMessage(
            $deviceId,
            $values['Today'],
            'kwh',
            $ts
        ));

        $this->bus->dispatch(new StateMessage(
            $deviceId,
            $values['Voltage'],
            'volt',
            $ts
        ));

        $this->bus->dispatch(new StateMessage(
            $deviceId,
            $values['Current'],
            'ampere',
            new \DateTimeImmutable($time)
        ));
    }
}
