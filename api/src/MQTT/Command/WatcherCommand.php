<?php

namespace Homebot\MQTT\Command;

use Homebot\Entity\EnergySensor;
use Homebot\MQTT\Bridge\Tasmota\EnergySubscriber;
use Homebot\MQTT\Watcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class WatcherCommand extends Command
{
    public function __construct(
        private Watcher $watcher,
        private MessageBusInterface $bus
    )
    {
        parent::__construct('mqtt:watch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $watcher = $this->watcher;
        $sensor = new EnergySensor();
        $sensor->driverInfo = ['topic'=> 'tele/em-homelab/SENSOR'];

        $subscriber = new EnergySubscriber($sensor, $this->bus);
        $watcher->addSubscriber($subscriber);

        // @codeCoverageIgnoreStart
        pcntl_signal(SIGINT, function (int $signal, $info) use ($watcher, $output) {
            $output->writeln('Receiving stop signal');
            $watcher->stop();
        });
        // @codeCoverageIgnoreEnd

        $output->writeln('Start subscribing mqtt topics');
        $watcher->start();

        return 0;
    }
}
