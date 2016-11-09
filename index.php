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

$app->any('/leave/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args) {
    $conditions = [
        new \Olek\Ifttt\Conditions\Evening(),
        new \Olek\Ifttt\Conditions\WeekDay(),
        new \Olek\Ifttt\Conditions\WifiDisconnected($request->getParams()),
        new \Olek\Ifttt\Conditions\WorkingDay(new \Checkdomain\Holiday\Util()),
    ];
    $client     = new \GuzzleHttp\Client();
    $actions    = [
//        new \Olek\Ifttt\Actions\Maker($client),
        new \Olek\Ifttt\Actions\FacebookMessage($client),
    ];
    (new \Olek\Ifttt\Application($conditions, $actions))->run();
    return $response;
});

$app->any('/fb/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args) {
    $verify_token     = getenv('FB_VERIFY_TOKEN');
    $hub_verify_token = null;

    if (isset($_REQUEST['hub_challenge']) && $_REQUEST['hub_verify_token'] == $verify_token) {
        echo $_REQUEST['hub_challenge'];
    }
});

$app->run();
