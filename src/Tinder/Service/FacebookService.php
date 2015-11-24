<?php

namespace Tinder\Service;

use Silex\Application;
use \Facebook\FacebookSession;
use \Facebook\FacebookRequest;

class FacebookService extends SessionService
{

    public function login()
    {
        if (isset($_GET['token']))
        {
            $this->saveSession('facebook_user_token', $_GET['token']);
            return $this->getSession('facebook_user_token');
        } else {
            $redirectUrl = $this->app['facebook.redirect'];
            $scope = implode(
              ',',
              array(
                'basic_info',
                'email',
                'public_profile',
                'user_about_me',
                'user_birthday',
                'user_education_history',
                'user_friends',
                'user_likes',
                'user_location',
                'user_photos',
                'user_relationship_details',
              )
            );
            $oauth = "https://www.facebook.com/dialog/oauth?client_id={$this->app['tinder.app.id']}&redirect_uri={$redirectUrl}&scope={$scope}&response_type=token";

            return $oauth;
        }
        return false;
    }

    public function logout()
    {
        $this->clearSession();
    }

    public function aboutMe()
    {
        $session = new FacebookSession($this->getSession('facebook_access_token'));
        $request = new FacebookRequest($session, 'GET', '/me');
        $user = $request->execute()->getResponse();

        $this->saveSession('facebook_user_name', $user->name);
        $this->saveSession('facebook_user_id', $user->id);
        $this->saveSession('facebook_access_token', $session->getToken());

        return $user;
    }

}