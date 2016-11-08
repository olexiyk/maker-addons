<?php
// Sends a request to maker if it's a working day evening.

include 'vendor/autoload.php';

$app = new \Slim\App();

$app->any('/', function ($request, $response, $args) {
    $holidayChecker = new \Checkdomain\Holiday\Util();
    $client         = new \GuzzleHttp\Client();
    $result         = (new \Olek\Ifttt\Leave($holidayChecker, $client))->run();
    $response->write($result);
    return $response;
});

$app->run();
