<?php
namespace Olek\Ifttt;

use Checkdomain\Holiday\Provider\DE;

class Leave
{
    /**
     * @var \Checkdomain\Holiday\Util
     */
    private $holidayChecker;
    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;
    /**
     * @var array
     */
    private $params;

    /**
     * Leave constructor.
     *
     * @param \Checkdomain\Holiday\Util $holidayChecker
     * @param \GuzzleHttp\Client        $httpClient
     * @param array                     $params
     */
    public function __construct(\Checkdomain\Holiday\Util $holidayChecker, \GuzzleHttp\Client $httpClient, array $params)
    {
        $this->holidayChecker = $holidayChecker;
        $this->httpClient     = $httpClient;
        $this->params         = $params;
    }


    /**
     * run
     *
     * @return string
     */
    public function run()
    {
        $url = 'https://maker.ifttt.com/trigger/leave/with/key/' . getenv('MAKER_KEY');
        if (
            !$this->holidayChecker->isHoliday('DE', 'now', DE::STATE_BE)
            && $this->isEvening()
            && $this->isWeekday()
            && $this->params["ConnectedToOrDisconnectedFrom"] == "disconnected from"
        ) {
            $res = $this->httpClient->request('POST', $url, ['json' => ['value1' => 'left']]);
            return 'request sent';
        } else {
            return 'request not sent';
        }
    }

    /**
     * isEvening
     *
     * @return bool
     */
    private function isEvening()
    {
        return date('H') >= 17 && date('H') <= 24;
    }

    /**
     * isWeekday
     *
     * @return bool
     */
    private function isWeekday()
    {
        return date('N') < 6;
    }

}
