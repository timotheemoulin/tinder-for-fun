<?php

namespace Tinder\Service;

use Silex\Application;

class TinderService extends SessionService
{
    private $headers = array(
      'Content-type: application/json',
      'app-version: 123',
      'User-Agent: Tinder/4.0.9 (iPhone; iOS 8.0.2; Scale/2.00)',
      'platform: ios',
    );

    public function getToken()
    {
        $token = $this->getSession('tinder_token');

        if (true || !$token) {
            $token = $this->callApi(
              'auth',
              false,
              array(
                'facebook_token' => $this->getSession('facebook_access_token'),
                'facebook_id' => $this->getSession('facebook_user_id'),
              )
            )->token;
        }

        $this->saveSession('tinder_token', $token);

        return $token;
    }

    public function aboutMe()
    {
        return $this->callApi(
          'auth',
          false,
          array(
            'facebook_id' => $this->getSession('facebook_user_id'),
            'facebook_token' => $this->getSession('facebook_access_token'),
          )
        );
    }

    public function getUser($matchId)
    {
        $id = str_replace($this->getSession('tinder_user_id'), '', $matchId);

        return $this->callApi("user/{$id}", true, array(), false);
    }

    public function getUpdates()
    {
        $result = $this->callApi('updates', true);

        return $result;
    }

    public function callApi($method, $private = true, $params = array(), $post = true)
    {
        $url = $this->app['tinder.api.url'].$method;

        $tokenHeader = '';

        if ($private) {
            $token = $this->getToken();
            $tokenHeader = " -H 'X-Auth-Token: {$token}'";
        }

        if ($post) {
            $data = json_encode($params);
            $data = "--data '{$data}'";
            $verb = '-X POST';
        } else {
            $data = '';
            $verb = 'GET';
        }

        $curl = "curl -v {$verb} '{$url}' -H 'app-version: 123' -H 'platform: ios' -H 'User-agent: Tinder/4.0.9 (iPhone; iOS 8.0.2; Scale/2.00)' -H 'content-type: application/json' {$tokenHeader} {$data}";

        $call = md5(serialize($curl));
        if ($this->getSession("api.{$call}")) {
            return $this->getSession("api.{$call}");
        }

        $result = exec($curl);

        if ($result = json_decode($result)) {
            if (isset($result->token)) {
                $this->saveSession('tinder_token', $result->token);
                $this->saveSession('tinder_user_id', $result->user->_id);
                $this->saveSession('tinder_user', $result->user);
            }

            $this->saveSession("api.{$call}", $result);

            return $result;
        }

        return false;
    }

}