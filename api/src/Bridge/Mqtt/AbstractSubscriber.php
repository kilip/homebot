<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Bridge\Mqtt;

use Homebot\Contracts\MqttSubscriberInterface;

abstract class AbstractSubscriber implements MqttSubscriberInterface
{
    public function __construct(
        protected readonly string $topic,
        protected readonly int $qos = 0)
    {
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getQos(): int
    {
        return $this->qos;
    }
}
