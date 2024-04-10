<?php

namespace Homebot\MQTT;

use Homebot\MQTT\SubscriberInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Contracts\MqttClient as MqttClientContract;

class Watcher
{
    private bool $useLoop = false;

    /**
     * @var SubscriberInterface[] $subscribers
     */
    public function __construct(
        private MqttClientContract $client,
        private ConnectionSettings $connectionSettings,
        private array $subscribers = []
    )
    {
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscribers[] = $subscriber;
    }

    public function start(): void
    {
        $client = $this->client;
        $settings = $this->connectionSettings;
        $useLoop = $this->useLoop;

        if($useLoop){
            pcntl_signal(SIGINT, function (int $signal, $info) use ($client) {
                $client->interrupt();
            });
        }

        $client->connect($settings);
        $client->loop($useLoop);
        $this->registerSubscribers($client);
    }

    public function stop(): void
    {
        $this->client->disconnect();
    }

    private function registerSubscribers(MqttClientContract $client): void
    {
        /** @var SubscriberInterface[] $subscribers */
        $subscribers = $this->subscribers;

        foreach($subscribers as $subscriber){
            $client->subscribe(
                $subscriber->getTopic(),
                $subscriber->getHandler(),
                $subscriber->getQos()
            );
        }
    }
}
