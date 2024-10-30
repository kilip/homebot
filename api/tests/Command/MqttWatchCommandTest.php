<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Tests\Command;

use Homebot\Contracts\MqttClientInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class MqttWatchCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        self::bootKernel();
        $mqttClient = $this->createMock(MqttClientInterface::class);
        self::getContainer()->set(MqttClientInterface::class, $mqttClient);

        $app = new Application(self::$kernel);
        $cmd = $app->find('mqtt:watch');
        $tester = new CommandTester($cmd);

        $mqttClient->expects($this->once())
            ->method('connect');

        $tester->execute([]);

        $tester->assertCommandIsSuccessful();
    }
}
