<?php

namespace Tinder\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class TinderController
{

    public function indexAction(Application $app, Request $request)
    {
        return $this->loginAction($app, $request);
    }

    public function loginAction(Application $app, Request $request, $callback = false)
    {
        if ($callback) {
            $app['service.tinder']->saveSession('facebook_access_token', $_GET['facebook_user_token']);

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
        $id = $request->attributes->get('id');
        $user = $app['service.tinder']->getUser($id);
        $updates = $app['service.tinder']->getUpdates();

        $messages = array();

        foreach ($updates as $update) {
            $matchId = $app['service.tinder']->extractMatchId($update->_id);

            if ($matchId == $id) {
                $messages = $update->messages;
                break;
            }
        }

        return $app['twig']->render(
          'tinder/user.html.twig',
          array(
            'user' => $user,
            'messages' => $messages,
          )
        );
    }

    public function recsAction(Application $app, Request $request)
    {
        $recs = $app['service.tinder']->getRecs();

//        $users = array();
//
//        foreach ($recs as $user) {
//            $users[] = $app['service.tinder']->getUser($user->_id);
//        }


        return $app['twig']->render(
          'tinder/recs.html.twig',
          array(
            'recs' => $recs,
          )
        );
    }

    public function likeAction(Application $app, Request $request)
    {
        $id = $request->attributes->get('id');
        $result = $app['service.tinder']->likeUser($id);

        return $result;
    }

    public function nopeAction(Application $app, Request $request)
    {
        $id = $request->attributes->get('id');
        $result = $app['service.tinder']->nopeUser($id);

        return $result;
    }

    public function updatesAction(Application $app, Request $request)
    {
        $updates = $app['service.tinder']->getUpdates();

        $users = array();

        foreach ($updates as $match) {
            $users[] = $app['service.tinder']->getUser($match->_id);
        }


        return $app['twig']->render(
          'tinder/updates.html.twig',
          array(
            'users' => $users,
          )
        );
    }
}
