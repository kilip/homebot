<?php

namespace Homelab\MQTT;

use Homebot\MQTT\SubscriberInterface;

abstract class AbstractSubscriber implements SubscriberInterface
{
    public function getQos(): int
    {
        return 0;
    }
}
