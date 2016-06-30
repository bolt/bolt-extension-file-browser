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
     * @return bool
     */
    public function hasSourceDir()
    {
        return $this->get('source', false) !== null;
    }

    /**
     * @return string
     */
    public function getSourceDir()
    {
        return $this->get('source');
    }

    /**
     * @param string $sourceDir
     *
     * @return Route
     */
    public function setSourceDir($sourceDir)
    {
        $this->set('source', $sourceDir);

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
