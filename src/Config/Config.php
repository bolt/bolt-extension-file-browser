<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Config;

/**
 * Main configuration class.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Config extends AbstractConfig
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
     * @param string $route
     *
     * @return Route
     */
    public function getRoute($route)
    {
        return $this->get('routes')->get($route);
    }

    /**
     * @return Routes
     */
    public function getRoutes()
    {
        return $this->get('routes');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialise()
    {
        if ($this->initialised === true) {
            return;
        }
        $this->set('routes', new Routes($this->parameters['routes']));
        parent::initialise();
    }
}
