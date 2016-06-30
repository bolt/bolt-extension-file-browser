<?php

namespace Bolt\Extension\Bolt\DirectoryIndex\Config;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Base configuration.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
abstract class AbstractConfig extends ParameterBag
{
    protected $initialised;

    /**
     * Peek at a key without building its value objects.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the parameter key does not exist
     * @param bool   $deep    If true, a path like foo[bar] will find deeper items
     *
     * @return mixed
     */
    public function peek($key, $default = null, $deep = false)
    {
        return parent::get($key, $default, $deep);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $deep = false)
    {
        $this->initialise();

        return parent::get($key, $default, $deep);
    }

    /**
     * Conditionally initialise keys with object values.
     *
     * @return mixed
     */
    protected function initialise()
    {
        if ($this->initialised === true) {
            return;
        }

        foreach ($this->parameters as $key => $value) {
            if (is_array($value)) {
                $this->set($key, new ParameterBag($value));
            } else {
                $this->set($key, $value);
            }
        }

        $this->initialised = true;
    }
}
