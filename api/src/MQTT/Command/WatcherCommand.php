<?php

namespace Homebot\MQTT\Command;

use Homebot\MQTT\Watcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WatcherCommand extends Command
{
    public function __construct(
        private Watcher $watcher
    )
    {
        parent::__construct('mqtt:watch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $watcher = $this->watcher;
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
