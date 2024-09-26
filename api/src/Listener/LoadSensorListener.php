<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Homebot\Listener;

use Homebot\Contracts\Repository\PowerMeterRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Homebot\Configuration;
use Homebot\Entity\Device;
use Homebot\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(event: Events::LOAD_CONFIGURATION)]
class LoadSensorListener
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $om
    ) {
    }

    public function __invoke(Configuration $configuration): void
    {
        $configs = $configuration->getConfig()['sensors'];
        if (isset($configs['power_meter'])) {
            $this->loadPowerMeter($configs['power_meter']);
        }
    }

    /**
     * @param array<string, mixed> $configs
     */
    private function loadPowerMeter(array $configs): void
    {
        $dispatcher = $this->dispatcher;
        $om = $this->om;

        /** @var PowerMeterRepositoryInterface $repo */
        $repo = $om->getRepository(Device::class);

        foreach ($configs as $config) {
            $meter = $repo->findByName($config['name']);

            // @codeCoverageIgnoreStart
            if (\is_null($meter)) {
                $meter = new Device();
            }
            // @codeCoverageIgnoreEnd

            $meter->name = $config['name'];
            $meter->notes = $config['notes'];
            $meter->driver = $config['driver'];
            $meter->driverConfig = $config['driverConfig'];

            $om->persist($meter);
            $om->flush();

            $dispatcher->dispatch($meter, Events::CONFIGURE_POWER_METER.'.'.$meter->driver);
        }
    }
}
