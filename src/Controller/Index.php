<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Controller;

use Bolt\Extension\Bolt\DirectoryIndex\Config\Config;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class Index implements ControllerProviderInterface
{
    /** @var Config */
    protected $config;

    /**
     * Constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
    }
}
