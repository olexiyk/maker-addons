<?php
namespace Olek\Ifttt\Actions;
class FacebookMessage implements ActionRunnerInterface
{

    /** @var \GuzzleHttp\Client */
    private $httpClient;

    /**
     * FacebookMessage constructor.
     *
     * @param \GuzzleHttp\Client $httpClient
     */
    public function __construct(\GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * run
     *
     * @return void
     */
    public function run()
    {
        $access_token = getenv('FB_ACCESS_TOKEN');
        $sender       = getenv('FB_ID');

        //API Url
        $url      = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $access_token;
        $jsonData = [
            "recipient" => [
                "id" => $sender,
            ],
            "message" => [
                "text" => "буду вдома за 35-40 хвилин",
            ],
        ];

        $this->httpClient->post($url, ['json' => $jsonData]);
    }
}
