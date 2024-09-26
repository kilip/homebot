<?php

/*
 * This file is part of the Homebot project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Homebot\Base;

trait ArrayAccessTrait
{
    /**
     * @var array<string,mixed>
     */
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->config[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->config[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->config[$offset]);
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->offsetExists($name) ? $this[$name] : $default;
    }

    public function set(string $name, string $value): void
    {
        $this->config[$name] = $value;
    }
}
