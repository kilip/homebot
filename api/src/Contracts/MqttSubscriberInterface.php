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

interface MqttSubscriberInterface
{
    public function getTopic(): string;

    public function getQos(): int;

    public function handler(MqttPayloadInterface $payload): void;
}
