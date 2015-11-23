<?php

use Symfony\Component\HttpFoundation\Response;

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.options' => array(
    'cache' => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
    'strict_variables' => true,
  ),
  'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
  'twig.path' => array(__DIR__ . '/../app/views', __DIR__ . '/../app/views/forms'),
));

$app['twig'] = $app->share($app->extend('twig', function (\Twig_Environment $twig, Silex\Application $app) {
    $twig->addExtension(new Tinder\Helper\TwigFormatter());

    return $twig;
}));

// Register services.
$app['service.tinder'] = $app->share(function ($app) {
    return new Tinder\Service\TinderService($app);
});
$app['service.facebook'] = $app->share(function ($app) {
    return new Tinder\Service\FacebookService($app);
});

// Register the error handler.
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 400:
        case 404:
            $message = $e->getMessage();
            break;
        default:
            $message = 'We are sorry, but something went wrong.';
    }

    return new Response($app['twig']->render('common/error.html.twig', array('message' => $message, 'code' => $code)), $code);
});

return $app;
