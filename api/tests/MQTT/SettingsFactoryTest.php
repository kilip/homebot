<?php

namespace Homebot\Tests\MQTT;

use Homebot\MQTT\SettingsFactory;
use PHPUnit\Framework\TestCase;

class SettingsFactoryTest extends TestCase
{
    public function testCreate()
    {
        $settings = SettingsFactory::create('toni', 'testing');

        $this->assertEquals('toni', $settings->getUsername());
        $this->assertEquals('testing', $settings->getPassword());
    }
}
