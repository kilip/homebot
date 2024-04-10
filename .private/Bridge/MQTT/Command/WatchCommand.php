<?php

namespace Homebot\Bridge\MQTT\Command;

use Homebot\Bridge\MQTT\Watcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WatchCommand extends Command
{
    public function __construct(
        private Watcher $watcher
    )
    {
        parent::__construct('mqtt:watch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->watcher->start();
        return 0;
    }
}
