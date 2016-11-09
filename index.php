<?php
// Sends a request to maker if it's a working day evening.

include 'vendor/autoload.php';

$container = new \Slim\Container();

$container['logger'] = function ($c) {
    $logger         = new Monolog\Logger('logger');
    $filename       = __DIR__ . '/access.log';
    $stream         = new Monolog\Handler\StreamHandler($filename, Monolog\Logger::DEBUG);
    $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
        $stream, Monolog\Logger::DEBUG);
    $logger->pushHandler($fingersCrossed);

    return $logger;
};

$app = new \Slim\App($container);

// logging middleware
$app->add(function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $next) {
    /** @var \Psr\Log\LoggerInterface $logger */
    $logger = $this->logger;
    $logger->info("Request", ["route" => $request->getUri()->getPath(), "method" => $request->getMethod(), "params" => $request->getParams()]);
    $response = $next($request, $response);
    $logger->info("Response", ["code" => $response]);
    return $response;
});

$app->any('/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args) {
    $holidayChecker = new \Checkdomain\Holiday\Util();
    $client         = new \GuzzleHttp\Client();
    $result         = (new \Olek\Ifttt\Leave($holidayChecker, $client))->run();
    $response->write($result);
    return $response;
});

$app->run();
