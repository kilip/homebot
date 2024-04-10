<?php

namespace Homebot\MQTT;

use PhpMqtt\Client\ConnectionSettings;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class SettingsFactory
{
    public static function create(
        #[Autowire('%mqtt.username%')]
        string $username,

        #[Autowire('%mqtt.password%')]
        string $password
    )
    {
        $settings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
        ;

        return $settings;
    }
}
