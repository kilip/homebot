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

interface MqttClientInterface
{
    public function connect(): void;

    public function disconnect(): void;

    public function addSubscriber(MqttSubscriberInterface $subscriber): void;
}
