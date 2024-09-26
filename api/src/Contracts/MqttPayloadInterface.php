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

interface MqttPayloadInterface
{
    public function getTopic(): string;

    public function getMessage(): string;

    public function isRetained(): bool;
}
