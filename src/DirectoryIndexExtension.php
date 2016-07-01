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
    public function getDisplayName()
    {
        return 'File & Directory Browser';
    }

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
        return [
            '/' => new Controller\Index(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return [
            'templates' => ['namespace' => 'DirectoryIndex'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig()
    {
        return [
            'assets'    => [
                'moment_js'     => true,
                'font_awesome'  => true,
            ],
            'routes'    => [
            ],
            'templates' => [
                'parent'    => '@DirectoryIndex/_default.twig',
                'index'     => '@DirectoryIndex/index.twig',
                'header'    => '@DirectoryIndex/_header.twig',
                'directory' => '@DirectoryIndex/_directory.twig',
                'file'      => '@DirectoryIndex/_file.twig',
            ],
        ];
    }
}
