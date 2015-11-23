<?php

namespace Tinder\Service;

use Facebook\FacebookSession;
use Silex\Application;

class FacebookHelper extends \Facebook\FacebookRedirectLoginHelper
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        require __DIR__.'/../../../vendor/facebook/php-sdk-v4/autoload.php';

        FacebookSession::setDefaultApplication($this->app['facebook.app.id'], $this->app['facebook.app.secret']);
        $redirectUrl = $this->app['application.url'].$this->app['url_generator']->generate('facebook_login_callback');

        parent::__construct($redirectUrl);
    }

    protected function storeState($state)
    {
        $this->app['session']->set('facebook.state', $state);
    }

    protected function loadState()
    {
        return $this->app['session']->get('facebook.state');
    }
}