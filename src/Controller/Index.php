<?php

namespace Bolt\Extension\Bolt\FileDirectoryBrowser\Controller;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Extension\Bolt\FileDirectoryBrowser\Config;
use Bolt\Extension\Bolt\FileDirectoryBrowser\FileDirectoryBrowserExtension;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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

        foreach ($this->config->getRoutes()->keys() as $mountName) {
            $route = $this->config->getRoute($mountName);
            $mountPath = $route->getMountPoint();
            $ctr->match($mountPath, [$this, 'index'])
                ->bind('directoryIndex_' . $mountName . '_base')
                ->value('route', $route)
                ->value('url', '')
                ->method('GET|POST')
            ;
            $ctr->match($mountPath . '/{url}', [$this, 'index'])
                ->bind('directoryIndex_' . $mountName)
                ->value('route', $route)
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
        $snippets = [];
        $this->config->set('general/add_jquery', true);

        // Moment.JS
        if ($this->config->isMomentJs()) {
            $snippet = new Snippet();
            $snippet
                ->setCallback('<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>')
                ->setLocation(Target::END_OF_BODY)
                ->setPriority(0)
            ;
            $snippets[] = $snippet;
        }

        if ($this->config->isFontAwesome()) {
            $snippet = new Snippet();
            $snippet
                ->setCallback('<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">')
                ->setLocation(Target::AFTER_HEAD_CSS)
            ;
            $snippets[] = $snippet;
        }

        foreach ($snippets as $snippet) {
            $app['asset.queue.snippet']->add($snippet);
        }

        /** @var FileDirectoryBrowserExtension $extension */
        $extension = $app['extensions']->get('Bolt/FileDirectoryBrowser');
        $dir = '/' . $extension->getWebDirectory()->getPath();
        $javaScript = new JavaScript();
        $javaScript
            ->setFileName($dir . '/directory-index.js')
            ->setLocation(Target::END_OF_HTML)
            ->setPriority(-10)
        ;
        $app['asset.queue.file']->add($javaScript);
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
     * @param Application  $app
     * @param Request      $request
     * @param Config\Route $route
     * @param string       $url
     *
     * @return Response
     */
    public function index(Application $app, Request $request, Config\Route $route, $url)
    {
        /** @var Config\Config $config */
        $config = $app['directory_index.config'];

        if (!$route->hasSourceDir()) {
            throw new \RuntimeException(sprintf('Mount "%s" is missing source directory value.', $route->getMountPoint()));
        }
        $sourceDir = $route->getSourceDir();
        $targetPath = $sourceDir . '/' . $url;
        if (is_file($targetPath)) {
            return new BinaryFileResponse($targetPath);
        }

        $fs = new Filesystem();
        if (!$fs->exists($targetPath)) {
            return 'Not found';
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
            'base'        => $request->getRequestUri(),
            'parent_dir'  => $this->getParentDirectoryName($route->getMountPoint(), $url),
        ];

        $html = $app['twig']->render($config->getTemplate('index'), $context);

        return new Response($html);
    }

    /**
     * @param string $mount
     * @param string $url
     *
     * @return bool|string
     */
    private function getParentDirectoryName($mount, $url)
    {
        if ($url === '') {
            return false;
        }

        return sprintf('/%s/%s', $mount, dirname($url) === '.' ? '' : dirname($url));
    }
}
