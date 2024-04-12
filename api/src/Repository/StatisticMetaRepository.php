<?php

namespace Homebot\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Homebot\Contracts\Repository\StatisticMetaRepositoryInterface;
use Homebot\Entity\Device;
use Homebot\Entity\StatisticMeta;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('doctrine.repository_service')]
/**
 * @extends ServiceEntityRepository<StatisticMeta>
 */
class StatisticMetaRepository extends ServiceEntityRepository implements StatisticMetaRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatisticMeta::class);
    }

    public function findBySensor(string $sensorId): ?StatisticMeta
    {
        return $this->findOneBy(['sensorId' => $sensorId]);
    }

    public function store(StatisticMeta $meta): StatisticMeta
    {
        $this->getEntityManager()->persist($meta);
        $this->getEntityManager()->flush();

        return $meta;
    }

    public function ensureMeta(Device $meter, string $sensorId, string $unit, bool $hasMean, bool $hasSum): StatisticMeta
    {
        $meta = $this->findBySensor($sensorId);

        // configure today meta
        if (\is_null($meta)) {
            $meta = new StatisticMeta(
                $meter->getId(),
                $sensorId,
                $unit,
                $hasMean,
                $hasSum
            );
            $this->store($meta);
        }

        return $meta;
    }

}
