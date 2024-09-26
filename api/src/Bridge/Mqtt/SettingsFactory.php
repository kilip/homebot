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

use PhpMqtt\Client\ConnectionSettings;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class SettingsFactory
{
    public function __construct(
        #[Autowire('%env(MQTT_USERNAME)%')]
        private string $username,

        #[Autowire('%env(MQTT_PASSWORD)%')]
        private string $password,
    ) {
    }

    public function __invoke(): ConnectionSettings
    {
        return (new ConnectionSettings())
            ->setUsername($this->username)
            ->setPassword($this->password)
        ;
    }
}
