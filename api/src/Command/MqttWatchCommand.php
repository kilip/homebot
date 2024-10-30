<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Command;

use Homebot\Configuration;
use Homebot\Contracts\MqttClientInterface;
use Homebot\Contracts\Repository\PowerMeterRepositoryInterface;
use Homebot\Events;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MqttWatchCommand extends Command
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly MqttClientInterface $mqttClient,
        private readonly PowerMeterRepositoryInterface $powerMeterRepository,
        private readonly Configuration $configuration
    ) {
        parent::__construct('mqtt:watch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mqttClient = $this->mqttClient;

        $this->dispatcher->dispatch($this->configuration, Events::LOAD_CONFIGURATION);

        $this->gatherSubscribers();

        // @codeCoverageIgnoreStart
        if (\function_exists('pcntl_signal')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, function () use ($mqttClient) {
                $mqttClient->disconnect();
            });
        }
        // @codeCoverageIgnoreEnd

        $mqttClient->connect();

        return 0;
    }

    private function gatherSubscribers(): void
    {
        $client = $this->mqttClient;
        $dispatcher = $this->dispatcher;

        $dispatcher->dispatch($client, Events::MQTT_REGISTER_SUBSCRIBER);
    }
}
