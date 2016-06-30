<?php

namespace Bolt\Extension\Bolt\DirectoryIndex;

use Bolt\Application;
use Bolt\Extension\SimpleExtension;

/**
 * Directory index extension loader.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class DirectoryIndexExtension extends SimpleExtension
{
    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $app['directory_index.config'] = $app->share(
            function () {
                return new Config\Config($this->getConfig());
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function registerFrontendControllers()
    {
        $app = $this->getContainer();
        $config = $this->getConfig();
        $mount = '/' . ltrim($config['mount'], '/');

        return [
            $mount => new Controller\Index($app['directory_index.config']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig()
    {
        return [
            'mount' => 'distribution',
        ];
    }
}
