<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Bridge\Tasmota;

use Homebot\Base\ArrayAccessTrait;

/**
 * @implements \ArrayAccess<string,mixed>
 */
class Config implements \ArrayAccess
{
    use ArrayAccessTrait;

    public function getTopic(): string
    {
        return $this->get('topic');
    }

    public function getQos(): int
    {
        return $this->get('qos', 0);
    }
}
