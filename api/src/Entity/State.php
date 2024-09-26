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
use Homebot\Contracts\StateRepositoryInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StateRepositoryInterface::class)]
class State
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $deviceId;

    #[ORM\Column(type: 'string')]
    public string $unit;

    #[ORM\Column(type: 'string')]
    public mixed $state;

    #[ORM\Column(type: 'string', nullable: true)]
    public mixed $lastState;

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $timestamp;

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
