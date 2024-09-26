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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Homebot\Contracts\MqttClientInterface;
use Homebot\Contracts\MqttSubscriberInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Contracts\MqttClient;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Client implements MqttClientInterface
{
    private bool $useLoop = true;

    /**
     * @var Collection<int,MqttSubscriberInterface>
     */
    private Collection $subscribers;

    public function __construct(
        private readonly MqttClient $mqttClient,
        private readonly ConnectionSettings $settings,

        #[Autowire('%env(APP_ENV)%')]
        string $env = 'test'
    ) {
        if ('test' === $env) {
            $this->useLoop = false;
        }

        $this->subscribers = new ArrayCollection([]);
    }

    /**
     * @throws ConfigurationInvalidException
     * @throws ConnectingToBrokerFailedException
     * @throws DataTransferException
     * @throws InvalidMessageException
     * @throws MqttClientException
     * @throws ProtocolViolationException
     */
    public function connect(): void
    {
        $mqtt = $this->mqttClient;
        $settings = $this->settings;

        $mqtt->connect($settings);
        $this->registerSubscriber();
        $mqtt->loop($this->useLoop);
    }

    /**
     * @throws DataTransferException
     */
    public function disconnect(): void
    {
        $this->mqttClient->interrupt();
        $this->mqttClient->disconnect();
    }

    public function addSubscriber(MqttSubscriberInterface $subscriber): void
    {
        if (!$this->subscribers->contains($subscriber)) {
            $this->subscribers->add($subscriber);
        }
    }

    /**
     * @throws RepositoryException
     * @throws DataTransferException
     */
    private function registerSubscriber(): void
    {
        $client = $this->mqttClient;

        foreach ($this->subscribers as $subscriber) {
            $callback = function (string $topic, string $message, bool $retained) use ($subscriber) {
                $payload = new Payload($topic, $message, $retained);
                $subscriber->handler($payload);
            };
            $client->subscribe($subscriber->getTopic(), $callback, $subscriber->getQos());
        }
    }
}
