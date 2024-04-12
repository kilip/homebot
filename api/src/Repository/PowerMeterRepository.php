<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Homebot\Contracts\Repository\PowerMeterRepositoryInterface;
use Homebot\Entity\Device;

/**
 * @extends ServiceEntityRepository<Device>
 */
class PowerMeterRepository extends ServiceEntityRepository implements PowerMeterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    public function listByDriver(string $driver): array
    {
        return $this->findBy(['driver' => $driver]);
    }

    public function findByName(mixed $name): ?Device
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function store(Device $meter): void
    {
        $em = $this->getEntityManager();
        $em->persist($meter);
        $em->flush();
    }
}
