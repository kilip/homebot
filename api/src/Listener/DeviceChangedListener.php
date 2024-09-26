<?php

namespace Homebot\Listener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Homebot\Entity\Device;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Device::class)]
class DeviceChangedListener
{
    public function preUpdate(): void
    {

    }
}
