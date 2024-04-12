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

class StatisticMessage
{
    public function __construct(
        public string $deviceId,
        public string $state,
        public string $timestamp
    ) {
    }
}
