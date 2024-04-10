<?php

namespace Homebot\MQTT;

use Homebot\MQTT\SubscriberInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Contracts\MqttClient as MqttClientContract;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Watcher
{
    private bool $useLoop = false;

    /**
     * @var SubscriberInterface[] $subscribers
     */
    public function __construct(
        private MqttClientContract $client,
        private ConnectionSettings $connectionSettings,
        private array $subscribers = [],

        #[Autowire('%env(APP_ENV)%')]
        string $env = 'test'
    )
    {
        if($env == 'test'){
            $this->useLoop = false;
        }
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

        $client->connect($settings);
        $this->registerSubscribers($client);
        $client->loop($useLoop);

        $client->disconnect();
    }

    public function stop(): void
    {
        $this->client->interrupt();
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
