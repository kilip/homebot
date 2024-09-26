<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Contracts;

use Homebot\Entity\State;
use Symfony\Component\Uid\Uuid;

interface StateRepositoryInterface
{
    public function store(State $state): void;

    public function findLast(Uuid $deviceId, string $unit): ?State;
}
