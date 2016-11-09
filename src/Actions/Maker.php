<?php
namespace Olek\Ifttt\Actions;
class Maker implements ActionRunnerInterface
{
    /** @var \GuzzleHttp\Client */
    private $httpClient;

    /**
     * Maker constructor.
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
        $this->httpClient->request('POST', 'https://maker.ifttt.com/trigger/leave/with/key/' . getenv('MAKER_KEY'), ['json' => ['value1' => 'left']]);
    }
}
