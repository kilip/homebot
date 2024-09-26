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

use Homebot\Events as HomebotEvents;

class TasmotaEvents
{
    public const CONFIGURE_POWER_METER = HomebotEvents::CONFIGURE_POWER_METER.'.tasmota';
}
