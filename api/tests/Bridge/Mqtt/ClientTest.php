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

use Homebot\Bridge\Mqtt\Client;
use Homebot\Contracts\MqttSubscriberInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Contracts\MqttClient;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private MockObject $mqttClient;

    private MockObject $settings;

    private Client $sut;

    public function setUp(): void
    {
        $this->mqttClient = $this->createMock(MqttClient::class);
        $this->settings = $this->createMock(ConnectionSettings::class);

        $this->sut = new Client(
            $this->mqttClient,
            $this->settings
        );
    }

    /**
     * @throws ConfigurationInvalidException
     * @throws ConnectingToBrokerFailedException
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws MqttClientException
     * @throws DataTransferException
     */
    public function testConnect(): void
    {
        $subscriber = $this->createMock(MqttSubscriberInterface::class);

        $subscriber->method('getTopic')->willReturn('topic');
        $subscriber->method('getQos')->willReturn(1);

        $this->mqttClient->expects($this->once())
            ->method('connect')
            ->with($this->settings);

        $this->mqttClient->expects($this->once())
            ->method('loop')
            ->with(false);

        $this->mqttClient->expects($this->once())
            ->method('subscribe')
            ->with('topic', [$subscriber, 'handler'], 1);

        $this->sut->addSubscriber($subscriber);
        $this->sut->connect();
    }

    /**
     * @throws DataTransferException
     */
    public function testDisconnect(): void
    {
        $this->mqttClient->expects($this->once())
            ->method('disconnect');

        $this->sut->disconnect();
    }
}
