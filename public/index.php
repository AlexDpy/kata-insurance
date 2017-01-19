<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

const RESPONSE_CODE = 200;

$app = new Silex\Application([
    'debug' => true,
]);

$app->register(new \Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../log/app.log',
]);


/** @var \Psr\Log\LoggerInterface $logger */
$logger = $app['logger'];

$calculator = new \Kata\Calculator(
    $logger
);


$app->get('/', function (Request $request) use ($logger) {



    return new \Symfony\Component\HttpFoundation\Response('', 204);
});

$app->post('/quote', function (Request $request) use ($logger, $calculator) {
    $json = $request->getContent();
    $data = json_decode($json, true);

    $logger->info('quote received', [
        'country' => $data['country'],
        'departureDate' => $data['departureDate'],
        'returnDate' => $data['returnDate'],
        'travellerAges' => $data['travellerAges'],
        'options' => $data['options'],
        'cover' => $data['cover']
    ]);

    try {
        $quote = new \Kata\Quote(
            $data['country'],
            new \DateTimeImmutable($data['departureDate']),
            new \DateTimeImmutable($data['returnDate']),
            $data['travellerAges'],
            $data['options'],
            $data['cover']
        );

        $result = $calculator->compute($quote);
        $logger->info('quote result: ' . $result);

        return new JsonResponse([
            'quote' => $result,
        ], RESPONSE_CODE);
    } catch (\Kata\BadRequestException $e) {
        $logger->warning('BadRequestException', [
            'message' => $e->getMessage(),
        ]);

        return new JsonResponse([], 400);
    }
});



$app->post('/feedback', function (Request $request) use ($logger) {
//    {"message":"Holy crap dudu, an error happened :(-> You will be charged of -0.0","type":"ERROR"}
    $json = $request->getContent();
    $logger->info($json);

    $data = json_decode($json, true);

    $logger->info($data['type'], [
        'message' => $data['message'],
    ]);


//    return new \Symfony\Component\HttpFoundation\Response('', 204);
    return new JsonResponse();
});

$app->run();
