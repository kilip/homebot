<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Contracts\Repository;

use Homebot\Entity\Device;

interface PowerMeterRepositoryInterface
{
    /**
     * @return array<int, Device>
     */
    public function listByDriver(string $driver): array;

    public function findByName(mixed $name): ?Device;

    public function store(Device $meter): void;
}
