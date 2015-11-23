<?php

namespace Tinder\Service;

use Silex\Application;

class SessionService
{
    protected $app;

    /**
     * @param \Silex\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    /**
     * @param null $key
     */
    public function clearSession($key = null)
    {
        if ($key) {
            $this->saveSession($key);
        } else {
            $this->app['session']->clear();
        }
    }

    /**
     * @param $key
     * @param null $value
     */
    public function saveSession($key, $value = null)
    {
        $this->app['session']->set($key, $value);
    }

    /**
     * @param null $key
     * @return bool
     */
    public function getSession($key = null)
    {
        return $this->app['session']->get($key);
    }
}