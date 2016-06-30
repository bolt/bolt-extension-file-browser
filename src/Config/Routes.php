<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Config;

/**
 * Route collection configuration.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Routes extends AbstractConfig
{
    /**
     * Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }

    /**
     * Get a single route's configuration.
     *
     * @param string $route
     *
     * @return Route|null
     */
    public function getRoute($route)
    {
        return $this->get($route);
    }

    /**
     * {@inheritdoc}
     */
    protected function initialise()
    {
        if ($this->initialised === true) {
            return;
        }

        foreach ($this->parameters as $key => $value) {
            $this->set($key, new Route($value));
        }

        parent::initialise();
    }
}
