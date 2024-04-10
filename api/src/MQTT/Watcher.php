<?php

namespace Homebot\MQTT;

use Homebot\MQTT\SubscriberInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Contracts\MqttClient as MqttClientContract;
use Psr\Log\LoggerInterface;
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
        private LoggerInterface $logger,
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
        $logger = $this->logger;

        foreach($subscribers as $subscriber){

            // @codeCoverageIgnoreStart
            $callback = function(string $topic, string $message, $retained) use($subscriber, $logger){
                $logger->info('topic {0}', [$topic]);
                $payload = new Payload($topic, $message, $retained);
                $subscriber->handle($payload);
            };
            // @codeCoverageIgnoreEnd

            $client->subscribe(
                $subscriber->getTopic(),
                $callback,
                $subscriber->getQos()
            );
        }
    }
}
