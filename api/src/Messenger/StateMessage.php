<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Messenger;

use Symfony\Component\Uid\Uuid;

class StateMessage
{
    public function __construct(
        public Uuid $deviceId,
        public string $state,
        public string $unit,
        public \DateTimeImmutable $timestamp,

    )
    {
    }
}
