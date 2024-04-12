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

use Homebot\Contracts\MqttPayloadInterface;

class Payload implements MqttPayloadInterface
{
    public function __construct(
        private readonly string $topic,
        private readonly string $message,
        private readonly bool $retained
    ) {
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isRetained(): bool
    {
        return $this->retained;
    }
}
