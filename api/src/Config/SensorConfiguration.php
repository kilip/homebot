<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class SensorConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('sensors');

        $root = $builder->getRootNode();

        $root
            ->children()
                ->arrayNode('power_meter')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('notes')->end()
                            ->scalarNode('type')->defaultValue('sensor')->end()
                            ->enumNode('driver')
                                ->values(['tasmota'])
                            ->end()
                            ->arrayNode('driverConfig')
                                ->scalarPrototype()->end()
                            ->end()
                       ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
