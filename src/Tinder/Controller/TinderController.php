<?php

namespace Tinder\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class TinderController
{

    public function indexAction(Application $app, Request $request)
    {
        return $app['twig']->render('index.html.twig');
    }

    public function loginAction(Application $app, Request $request, $callback = false)
    {
        if ($callback) {
            $app['service.facebook']->saveSession('facebook_access_token', $_GET['facebook_user_token']);
            return $app->redirect($app['url_generator']->generate('tinder_me'));
        } else {
            $oauth = $app['service.facebook']->login();
            return $app['twig']->render('facebook/oauth.html.twig', array('oauth' => $oauth));
        }

    }

    public function logoutAction(Application $app, Request $request)
    {
        $app['service.facebook']->logout();

        return $app->redirect($app['url_generator']->generate('homepage'));
    }

    public function meAction(Application $app, Request $request)
    {
        $user = $app['service.tinder']->aboutMe()->user;


        return $app['twig']->render(
          'tinder/me.html.twig',
          array(
            'user' => $user,
          )
        );
    }

    public function userAction(Application $app, Request $request)
    {
        $user = $app['service.tinder']->getUser($request->attributes->get('id'));


        return $app['twig']->render(
          'tinder/user.html.twig',
          array(
            'user' => $user->results,
          )
        );
    }

    public function updatesAction(Application $app, Request $request)
    {
        $updates = $app['service.tinder']->getUpdates();

        $matches = array();

        foreach ($updates->matches as $match) {
            $matches[] = $app['service.tinder']->getUser($match->_id);
        }


        return $app['twig']->render(
          'tinder/updates.html.twig',
          array(
            'matches' => $matches->results,
          )
        );
    }
}
