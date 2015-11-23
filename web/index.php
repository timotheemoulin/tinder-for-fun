<?php
define('APP_PUBLIC_ROOT', __DIR__);
$env = getenv('APP_ENV') ?: 'prod';

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

require __DIR__.'/../src/app.php';
require __DIR__.'/../app/config/'.$env.'.php';
require __DIR__.'/../src/routes.php';

$app->run();
