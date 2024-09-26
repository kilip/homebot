<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Tests\Bridge\Mqtt;

use Homebot\Bridge\Mqtt\SettingsFactory;
use PhpMqtt\Client\ConnectionSettings;
use PHPUnit\Framework\TestCase;

class SettingsFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new SettingsFactory('testing', 'testing');

        $settings = $factory();

        $this->assertInstanceOf(ConnectionSettings::class, $settings);
        $this->assertSame('testing', $settings->getUsername());
        $this->assertSame('testing', $settings->getPassword());
    }
}
