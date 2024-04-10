<?php

namespace Homebot\MQTT;

interface SubscriberInterface {
    public function getTopic(): string;
    public function handle(Payload $payload): void;
    public function getQos(): int;
}
