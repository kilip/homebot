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
use Gedmo\Mapping\Annotation as Gedmo;
use Homebot\Repository\PowerMeterRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PowerMeterRepository::class)]
#[ORM\Index(fields: ['name'], name: 'idx_name')]
#[ORM\Index(fields: ['deviceId'], name: 'idx_device_id')]
class Device
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    private string $deviceId;

    #[ORM\Column(type: 'string', unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $notes;

    #[ORM\Column(type: 'string')]
    private string $driver;

    /**
     * @var array<string,mixed>
     */
    #[ORM\Column(type: 'json')]
    private array $driverConfig;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function setDriver(string $driver): void
    {
        $this->driver = $driver;
    }

    public function getDriverConfig(): array
    {
        return $this->driverConfig;
    }

    public function setDriverConfig(array $driverConfig): void
    {
        $this->driverConfig = $driverConfig;
    }
}
