<?php

namespace Homebot\MQTT;

interface SubscriberInterface {
    public function getTopic(): string;
    public function getHandler(): callable;
    public function getQos(): int;
}
