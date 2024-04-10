<?php

namespace Homebot\Bridge\MQTT;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Repositories\MemoryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * MQTT Client Watcher
 */
class Watcher
{
    private MqttClient $client;
    private ConnectionSettings $settings;

    public function __construct(
        #[Autowire('%env(MQTT_SERVER)%')]
        private string $host,

        #[Autowire('%env(int:MQTT_PORT)%')]
        private int $port,

        #[Autowire('%env(MQTT_USERNAME)%')]
        private string $username,

        #[Autowire('%env(MQTT_PASSWORD)%')]
        private string $password,

        private LoggerInterface $logger
    )
    {
        $this->settings = (new ConnectionSettings())
            ->setConnectTimeout(5)
            ->setUsername($this->username)
            ->setPassword($this->password)
        ;

        $this->client = new MqttClient(
            $this->host,
            $this->port,
            'homebot',
            MqttClient::MQTT_3_1_1,
            new MemoryRepository(),
            $logger
        );

    }

    public function start()
    {
        $client = $this->client;
        $settings = $this->settings;
        $logger = $this->logger;


        $client->connect($settings);
        $client->subscribe('#', function($topic, $message, $retained, $matchedWildCards) use ($logger){
            $logger->warning(sprintf('%s: %s', $topic, $message));
        });
        $client->loop(true);
        $client->disconnect();
    }
}
