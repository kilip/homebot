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

use Homebot\Bridge\Tasmota\Subscriber\PowerMeterSubscriber;
use Homebot\Contracts\MqttClientInterface;
use Homebot\Contracts\Repository\PowerMeterRepositoryInterface;
use Homebot\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: Events::MQTT_REGISTER_SUBSCRIBER)]
readonly class RegisterMqttSubscriberListener
{
    public function __construct(
        private MessageBusInterface $bus,
        private PowerMeterRepositoryInterface $repository,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(MqttClientInterface $client): void
    {
        $this->registerPowerMeter($client);
    }

    private function registerPowerMeter(MqttClientInterface $client): void
    {
        $meters = $this->repository->listByDriver('tasmota');
        $bus = $this->bus;
        $logger = $this->logger;

        foreach ($meters as $meter) {
            $subscriber = new PowerMeterSubscriber($bus, $meter);
            $client->addSubscriber($subscriber);
            $logger->notice('added subscriber for power meter {0}', [$meter->name]);
        }
    }
}
