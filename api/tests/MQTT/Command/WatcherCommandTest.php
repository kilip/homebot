<?php

namespace Homebot\Testing\MQTT\Command;

use PhpMqtt\Client\Contracts\MqttClient;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class WatcherCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $client = $this->createMock(MqttClient::class);
        self::bootKernel();
        self::$kernel->getContainer()->set('mqtt.client', $client);

        $app = new Application(self::$kernel);

        $cmd = $app->find('mqtt:watch');
        $tester = new CommandTester($cmd);

        $tester->execute([]);

        $tester->assertCommandIsSuccessful();

        $output = $tester->getDisplay();
        $this->assertStringContainsString('Start subscribing mqtt topics', $output);
    }
}
