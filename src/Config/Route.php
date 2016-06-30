<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Config;

/**
 * Route configuration.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class Route extends AbstractConfig
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
     * @return bool
     */
    public function hasMountPoint()
    {
        return $this->getBoolean('mount', false);
    }

    /**
     * @return string
     */
    public function getMountPoint()
    {
        return $this->get('mount');
    }

    /**
     * @param string $mountPoint
     *
     * @return Route
     */
    public function setMountPoint($mountPoint)
    {
        $this->set('mount', $mountPoint);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialise()
    {
        if ($this->initialised === true) {
            return;
        }

        parent::initialise();
    }
}
