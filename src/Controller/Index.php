<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Controller;

use Bolt\Extension\Bolt\DirectoryIndex\Config;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Index implements ControllerProviderInterface
{
    /** @var Config\Config */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $this->config = $app['directory_index.config'];

        /** @var $ctr ControllerCollection */
        $ctr = $app['controllers_factory'];

        /**
         * @var string       $mount
         * @var Config\Route $route
         */
        foreach ($this->config->getRoutes()->all() as $mount => $route) {
            $mount = rtrim(ltrim($mount, '/'), '/');
            $ctr->match($mount, [$this, 'index'])
                ->bind('directoryIndex_' . $mount . '_base')
                ->value('mount', $mount)
                ->value('url', '')
                ->method('GET|POST')
            ;
            $ctr->match($mount . '/{url}', [$this, 'index'])
                ->bind('directoryIndex_' . $mount)
                ->value('mount', $mount)
                ->assert('url', '.+')
                ->method('GET|POST')
            ;
        }

        $ctr->before([$this, 'before']);
        $ctr->after([$this, 'after']);

        return $ctr;
    }

    /**
     * @param Request     $request
     * @param Application $app
     */
    public function before(Request $request, Application $app)
    {
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param Application $app
     */
    public function after(Request $request, Response $response, Application $app)
    {
    }

    /**
     * @param Application $app
     * @param Request     $request
     * @param string      $mount
     * @param string      $url
     *
     * @return Response
     */
    public function index(Application $app, Request $request, $mount, $url)
    {
        /** @var Config\Config $config */
        $config = $app['directory_index.config'];
        $mountConfig = $config->getRoute($mount);

        if (!$mountConfig->hasSourceDir()) {
            throw new \RuntimeException(sprintf('Mount "%s" is missing source directory value.', $mount));
        }
        $sourceDir = $mountConfig->getSourceDir();
        $targetPath = $sourceDir . '/' . $url;
        if (is_file($targetPath)) {
            return $this->handleDownload($app, $config, $targetPath);
        }

        $fs = new Filesystem();
        if (!$fs->exists($targetPath)) {
            return 'Nadda here';
        }

        $directories = new Finder();
        $directories
            ->directories()
            ->in($targetPath)
            ->depth('== 0')
            ->sortByName()
        ;

        $files = new Finder();
        $files
            ->files()
            ->in($targetPath)
            ->depth('== 0')
            ->sortByName()
        ;

        $context = [
            'templates'     => [
                'parent'    => $config->getTemplate('parent'),
                'index'     => $config->getTemplate('index'),
                'header'    => $config->getTemplate('header'),
                'directory' => $config->getTemplate('directory'),
                'file'      => $config->getTemplate('file'),
            ],
            'directories' => $directories,
            'files'       => $files,
            'base'        => $request->getRequestUri()
        ];

        $html = $app['twig']->render($config->getTemplate('index'), $context);

        return new Response($html);
    }

    protected function handleDownload(Application $app, Config\Config $config, $targetPath)
    {

    }
}
