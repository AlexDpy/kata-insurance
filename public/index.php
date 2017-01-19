<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application([
    'debug' => true,
]);

$app->register(new \Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../log/app.log',
]);


/** @var \Psr\Log\LoggerInterface $logger */
$logger = $app['logger'];


$app->get('/', function (Request $request) use ($logger) {



    return new JsonResponse();
});

$app->run();
