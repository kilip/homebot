<?php

namespace Homebot\Tests\MQTT;

use Homebot\MQTT\Watcher;
use Homebot\MQTT\SubscriberInterface;
use PhpMqtt\Client\ConnectionSettings;
use PHPUnit\Framework\TestCase;
use PhpMqtt\Client\Contracts\MqttClient as MqttClientContract;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers Homebot\MQTT\Watcher
 */
class WatcherTest extends TestCase
{
    /**
     * @var MockObject|MqttClientContract
     */
    private $client;

    /**
     * @var MockObject|ConnectionSettings
     */
    private $settings;

    /**
     * @var Watcher
     */
    private $watcher;

    protected function setUp(): void
    {
        $this->client = $this->createMock(MqttClientContract::class);
        $this->settings = $this->createMock(ConnectionSettings::class);
        $this->watcher = new Watcher($this->client, $this->settings);
    }

    public function testStart()
    {
        $watcher = $this->watcher;
        $client = $this->client;
        $subscriber = $this->createMock(SubscriberInterface::class);
        $callback = function(){};

        $subscriber->method('getTopic')->willReturn('test');
        $subscriber->method('getQos')->willReturn(0);
        $subscriber->method('getHandler')->willReturn($callback);

        $watcher->addSubscriber($subscriber);
        $watcher->addSubscriber($subscriber);

        $client
            ->expects($this->once())
            ->method('connect');
        $client->expects($this->once())->method('loop')->with(false);

        $client->expects($this->exactly(2))
            ->method('subscribe')
            ->with('test', $callback, 0);

        $watcher->start();
    }
}
