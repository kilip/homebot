<?php

namespace Homebot\Messenger\Handler;

use Homebot\Sensor\EnergyUpdate;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class EnergyHandler
{
    public function __invoke(EnergyUpdate $message)
    {
    }
}
