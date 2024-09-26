<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot;

use Homebot\Config\SensorConfiguration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    /**
     * @var array<string, mixed>
     */
    private array $config = [];

    public function __construct(
        #[Autowire('%homebot.config_dir%')]
        private readonly string $configDir = '../../config/homebot'
    ) {
        $this->loadConfig();
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    private function loadConfig()
    {
        $config = Yaml::parseFile($this->configDir.'/sensors.yaml');
        $processor = new Processor();
        $configuration = new SensorConfiguration();

        $processed = $processor->processConfiguration($configuration, $config);
        $this->config['sensors'] = $processed;
    }
}
