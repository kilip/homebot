<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot;

class Events
{
    public const MQTT_REGISTER_SUBSCRIBER = 'mqtt.register.subscriber';
    public const LOAD_CONFIGURATION = 'homebot.configuration.load';
    public const CONFIGURE_POWER_METER = 'homebot.power_meter.configure';
}
