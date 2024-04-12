<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Entity;

use Doctrine\ORM\Mapping as ORM;
use Homebot\Repository\StatisticMetaRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StatisticMetaRepository::class)]
class StatisticMeta
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    public function __construct(
        #[ORM\Column(type: UuidType::NAME)]
        public Uuid $deviceId,

        #[ORM\Column(type: 'string')]
        public string $sensorId,

        #[ORM\Column(type: 'string')]
        public string $unit,

        #[ORM\Column(type: 'boolean')]
        public bool $hasMean,

        #[ORM\Column(type: 'boolean')]
        public bool $hasSum,
    ) {
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
