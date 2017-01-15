<?php

namespace Bolt\Extension\Bolt\FileDirectoryBrowser\Event;

use Bolt\Extension\Bolt\FileDirectoryBrowser\Config;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Browser event.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class BrowserEvent extends Event
{
    /** @var Request */
    private $request;
    /** @var Config\Route */
    private $route;
    /** @var string */
    private $url;

    /**
     * Constructor.
     *
     * @param Request      $request
     * @param Config\Route $route
     * @param string       $url
     */
    public function __construct(Request $request, Config\Route $route, $url)
    {
        $this->request = $request;
        $this->route = $route;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        $sourceDir = $this->route->getSourceDir();

        return $sourceDir . '/' . $this->url;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     *
     * @return BrowserEvent
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return Config\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param Config\Route $route
     *
     * @return BrowserEvent
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return BrowserEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
