<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Controller;

use Bolt\Extension\Bolt\DirectoryIndex\Config;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
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
            $ctr->match($mount, [$this, 'index'])
                ->bind('directoryIndex_' . $mount)
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
     */
    public function index(Application $app, Request $request)
    {
    }
}
