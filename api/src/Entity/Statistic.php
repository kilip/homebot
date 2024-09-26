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
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Statistic
{

    #[ORM\Id]
    #[ORM\Column(type: 'bigint')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Statistic::class)]
    #[ORM\JoinColumn(name: 'lastStateId', referencedColumnName: 'id', nullable: true)]
    public ?Uuid $lastStateId = null;

    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $deviceId;

    public \DateTimeImmutable $timestamp;

    #[ORM\Column(type: 'float', precision: 20, scale: 10)]
    public float $state;

    /**
     * Calculate difference between current state and last state.
     */
    #[ORM\Column(type: 'float', precision: 20, scale: 10)]
    public float $delta = 0;

    public function getId(): ?int
    {
        return $this->id;
    }
}
