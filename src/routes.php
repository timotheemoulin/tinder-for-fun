<?php

$app->get('/', 'Tinder\Controller\TinderController::indexAction')->bind('homepage');

// Facebook
$app->get('/login/callback', 'Tinder\Controller\TinderController::loginAction')->bind('facebook_login_callback')->value('callback', true);
$app->get('/login', 'Tinder\Controller\TinderController::loginAction')->bind('facebook_login');
$app->get('/logout', 'Tinder\Controller\TinderController::logoutAction')->bind('facebook_logout');

// Tinder
$app->get('/tinder', 'Tinder\Controller\TinderController::meAction')->bind('tinder_me');
$app->get('/tinder/updates', 'Tinder\Controller\TinderController::updatesAction')->bind('tinder_updates');
$app->get('/tinder/user/{id}', 'Tinder\Controller\TinderController::userAction')->bind('tinder_user');
$app->get('/tinder/user/{id}/send', 'Tinder\Controller\TinderController::sendAction')->bind('tinder_send');
$app->get('/tinder/recs', 'Tinder\Controller\TinderController::recsAction')->bind('tinder_recs');
$app->get('/tinder/user/{id}/like', 'Tinder\Controller\TinderController::likeAction')->bind('tinder_like');
$app->get('/tinder/user/{id}/nope', 'Tinder\Controller\TinderController::nopeAction')->bind('tinder_nope');