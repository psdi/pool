<?php

namespace Pool\Container;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * PSR-11 compliant container class
 * Inspired by:
 *  - https://github.com/AcclimateContainer/acclimate-container
 */
class Container implements ContainerInterface
{
    private $dependencies = [];

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->dependencies[$id];
        }

        throw new NotFoundException($id);
    }

    /**
     * {@inheritDoc}
     */
    public function has($id): bool
    {
        return key_exists($id, $this->dependencies);
    }

    /**
     * Sets an identifier-dependency pair
     * 
     * @param string|mixed $identifier
     * @param mixed $dependency
     */
    public function set($identifier, $dependency): void
    {
        $this->dependencies[$identifier] = $dependency;
    }
}