<?php

namespace Homebot\MQTT;

class Payload
{
    public function __construct(
        public string $topic,
        public string $message,
        public bool $retained = false,
        public string $matchedWildcards = ""
    )
    {
    }
}
