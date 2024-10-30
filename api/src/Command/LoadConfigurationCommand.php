<?php

namespace Homebot\Command;

use Homebot\Configuration;
use Homebot\Events;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class LoadConfigurationCommand extends Command
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private Configuration $configuration
    )
    {
        parent::__construct('homebot:config:load');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->dispatcher->dispatch($this->configuration, Events::LOAD_CONFIGURATION);

        return 0;
    }
}
