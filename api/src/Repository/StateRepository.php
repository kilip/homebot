<?php

namespace Homebot\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Homebot\Contracts\StateRepositoryInterface;
use Homebot\Entity\State;
use Symfony\Component\Uid\Uuid;

class StateRepository extends ServiceEntityRepository implements StateRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }

    public function store(State $state): void
    {
        $this->getEntityManager()->persist($state);
        $this->getEntityManager()->flush();
    }

    public function findLast(Uuid $deviceId, string $unit): ?State
    {
        return $this->findOneBy(['deviceId' => $deviceId, 'unit' => $unit], ['timestamp' => 'DESC']);
    }

}
