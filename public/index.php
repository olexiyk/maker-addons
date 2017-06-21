<?php
include '../vendor/autoload.php';

date_default_timezone_set(getenv('TZ') ?: 'UTC');

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

// leave triggered
$app->any('/leave/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args) {
    $conditions = [
        new \Olek\Ifttt\Conditions\Evening(),
        new \Olek\Ifttt\Conditions\WeekDay(),
        new \Olek\Ifttt\Conditions\WifiDisconnected($request->getParams()),
        new \Olek\Ifttt\Conditions\WorkingDay(new \Checkdomain\Holiday\Util()),
    ];
    $client     = new \GuzzleHttp\Client();
    $actions    = [
        new \Olek\Ifttt\Actions\FacebookMessage($client),
    ];
    (new \Olek\Ifttt\Application($conditions, $actions))->run();
    return $response;
});

// facebook verification
$app->any('/fb/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args) {
    $verify_token     = getenv('FB_VERIFY_TOKEN');
    $hub_verify_token = null;

    if (isset($_REQUEST['hub_challenge']) && $_REQUEST['hub_verify_token'] == $verify_token) {
        echo $_REQUEST['hub_challenge'];
    }
});

// google calendar verification
$app->any('/oauthcallback/', function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args) {
    session_start();

    $client = new Google_Client();
    $config = unserialize(getenv('GOOGLE_CONFIG'));
    $client->setAuthConfig($config);
    $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);

    if (!isset($_GET['code'])) {
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    } else {
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        $redirect_uri             = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
});

$app->run();
